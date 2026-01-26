<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'zone' => $this->whenLoaded('zone', function () {
                return [
                    'id' => $this->zone->id,
                    'name' => $this->zone->name,
                ];
            }),
            'island' => $this->whenLoaded('island', function () {
                return [
                    'id' => $this->island->id,
                    'name' => $this->island->name,
                ];
            }),
        ];
    }
}

