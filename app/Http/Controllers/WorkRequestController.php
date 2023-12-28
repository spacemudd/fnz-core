<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\WorkRequestCollection;
use App\Http\Resources\WorkRequestResource;
use App\Jobs\Repair360WebhookJob;
use App\Models\WorkRequest;
use Illuminate\Http\Request;

class WorkRequestController extends Controller
{
    /**
     * All work requests
     * @return AnonymousResourceCollection<LengthAwarePaginator<WorkRequestResource>>
     */
    public function index()
    {
        return new WorkRequestCollection(WorkRequest::paginate(10));
    }

    /**
     * Store a new work request.
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            'ref' => 'required',
            'vin' => 'required',
            'item_description' => 'required',
            'item_part_number' => 'required',
            'qty' => 'required|numeric',
            'make' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'year' => 'nullable|numeric|digits:4',
            /**
             * The URL at which the system will send a GET request with the work request detail when updated.
             * @var string
             * @example https://webhook.site/f7d0ecb1-29ff-4c83-a993-9ef75c33bcde
             */
            'webhook_url_at' => 'nullable|url',
        ]);

        $wo = WorkRequest::create($request->all());
        $wo->refresh();

        dispatch(new Repair360WebhookJob($wo));

        return new WorkRequestResource($wo);
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

        if (!$wo->fnz_priced_at) {
            return response()->json([
                'message' => 'Work request ('.$wo->ref.') has not been priced yet.',
            ], 400);
        }

        $wo->approval_ref = $request->approval_ref;
        $wo->approved_at = now();
        $wo->save();

        return new WorkRequestResource($wo);
    }
}
