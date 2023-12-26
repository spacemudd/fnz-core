<?php

namespace App\Http\Resources;

use App\Models\WorkRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($workRequest) {
                return new WorkRequestResource($workRequest);
            })->toArray();
    }
}
