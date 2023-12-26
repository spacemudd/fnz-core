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
            'ref' => $this->ref,
            'vin' => $this->vin,
            'item_description' => $this->item_description,
            'item_part_number' => $this->item_part_number,
            'qty' => $this->qty,
            'make' => $this->make,
            'model' => $this->model,
            'year' => $this->year,
            /** @var string $content The status are as following: pending, completed, unavailable. */
            'status' => $this->status,
            /** @var $content If null, the item has not been priced yet. If 0, there is no item available. */
            'is_available_qty' => $this->is_available_qty,
            'fnz_price' => $this->fnz_price,
            /** @var string $content A timestamp of when FNZ priced the item. */
            'fnz_priced_at' => $this->fnz_priced_at,
            /** @var string $content A reference ID the requester can use. (e.g. purchase order number) */
            'approval_ref' => $this->approval_ref,
             /** @var string $content The timestamp of when the requester approved the price. */
            'approved_at' => $this->approved_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
