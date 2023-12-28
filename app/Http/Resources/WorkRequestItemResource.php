<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkRequestItemResource extends JsonResource
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
            'work_request_id' => $this->work_request_id,
            /** @var string $content Item description.  */
            'description' => $this->description,
            /** @var string $content The part number of the item.  */
            'part_number' => $this->part_number,
            /** @var string $content The qty required to be priced.  */
            'required_qty' => $this->required_qty,
            /** @var string $content The available qty that can be provided.  */
            'available_qty' => $this->available_qty,
            'price' => $this->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
