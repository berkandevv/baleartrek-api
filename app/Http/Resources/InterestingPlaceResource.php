<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterestingPlaceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gps' => $this->gps,
            'order' => $this->whenPivotLoaded('interesting_place_trek', function () {
                return $this->pivot->order;
            }),
            'place_type' => new PlaceTypeResource($this->whenLoaded('placeType')),
        ];
    }
}

