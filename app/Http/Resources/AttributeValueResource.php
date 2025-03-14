<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attribute_id' => $this->whenLoaded('attribute')->id,
            'name' => $this->whenLoaded('attribute')->name,
            'id' => $this->id,
            'value' => $this->value,
        ];
    }
}
