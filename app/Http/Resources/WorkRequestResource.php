<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'vin' => $this->vin,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            /** @var string $content The status are as following: pending, completed, unavailable. */
            'status' => $this->status,
            /** @var string $content The timestamp of when the work request must be priced depending on priority. */
            'deadline_at' => $this->priced_at,
            /** @var string $content The timestamp of when the item(s) were priced at. */
            'priced_at' => $this->priced_at,
             /** @var string $content The timestamp of when the requester approved the price. */
            'approved_at' => $this->approved_at,
            /** @var string $content A reference ID the requester can use. (e.g. purchase order number) */
            'approval_ref' => $this->approval_ref,
            /** @var array<array, \App\Http\Resources\WorkRequestItemResource> */
            'items' => new WorkRequestItemsCollection($this->items),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
