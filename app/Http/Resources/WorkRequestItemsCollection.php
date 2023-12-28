<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WorkRequestItemsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<\App\Http\Resources\WorkRequestItemResource>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($workRequest) {
                return new WorkRequestItemResource($workRequest);
            })->toArray();
    }
}
