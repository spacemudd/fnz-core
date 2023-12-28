<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\WorkRequestCollection;
use App\Http\Resources\WorkRequestResource;
use App\Jobs\Repair360WebhookJob;
use App\Models\WorkRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkRequestController extends Controller
{
    /**
     * All work requests
     * @return AnonymousResourceCollection<LengthAwarePaginator<WorkRequestResource>>
     */
    public function index()
    {
        return new WorkRequestCollection(WorkRequest::with('items')->paginate(10));
    }

    /**
     * Store a new work request.
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            /**
             * A unique reference ID that can be used by the requester.
             * @var string
             * @example CSK19288
             */
            'reference' => 'required|string|max:255|unique:work_requests',
            /**
             * Required when Make/Model/Year not present.
             * @var string
             * @example 1HGCM82633A004352
             */
            'vin' => 'required_without:make,model,year|string|max:255',
            /**
             * Required when VIN not present.
             * @var string
             * @example Honda
             */
            'make' => 'nullable|string|max:255|required_without:vin',
            /**
             * Required when VIN not present.
             * @var string
             * @example Accord
             */
            'model' => 'nullable|string|max:255|required_without:vin',
            /**
             * Required when VIN not present.
             * @var int
             * @example 2024
             */
            'year' => 'nullable|numeric|digits:4|required_without:vin',
            'items.*.description' => 'required|string|max:255',
            'items.*.part_number' => 'required|string|max:255',
            'items.*.required_qty' => 'required|numeric|min:1',
            /**
             * The date and time by which the work request must be completed at. (UTC time)
             * @var string
             * @example 2024-12-31T23:59:59Z
             */
            'deadline_at' => 'nullable|date',
            /**
             * The URL at which the system will send a GET request with the work request detail when updated.
             * @var string
             * @example https://webhook.site/f7d0ecb1-29ff-4c83-a993-9ef75c33bcde
             */
            'webhook_url_at' => 'nullable|url',
        ]);

        DB::beginTransaction();
        $wr = WorkRequest::create($request->except('items'));
        $wr->items()->createMany($request->items);
        $wr->refresh();
        DB::commit();

        dispatch(new Repair360WebhookJob($wr));

        return new WorkRequestResource($wr);
    }

    /**
     * Show a specific work request.
     */
    public function show(string $id)
    {
        return new WorkRequestResource(WorkRequest::findOrFail($id));
    }

    /**
     * Approve a specific work request.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return void
     */
    public function approve(Request $request, string $id)
    {
        $request->validate([
            /**
             * The approval reference number (e.g. PO number) for the work request.
             * @var string
             * @example PO-12345
             */
            'approval_ref' => 'required|string|max:255',
        ]);

        $wo = WorkRequest::findOrFail($id);

        if (!$wo->priced_at) {
            return response()->json([
                'message' => 'Work request ('.$wo->reference.') has not been priced yet.',
            ], 400);
        }

        $wo->approval_ref = $request->approval_ref;
        $wo->approved_at = now();
        $wo->save();

        return new WorkRequestResource($wo);
    }
}
