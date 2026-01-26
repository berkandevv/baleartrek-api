<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrekResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'regNumber' => $this->regnumber,
            'name' => $this->name,
            'status' => $this->status,
            'score' => [
                'total' => $this->totalScore,
                'count' => $this->countScore,
                'average' => $this->countScore > 0
                    ? round($this->totalScore / $this->countScore, 2)
                    : null,
            ],
            'municipality' => new MunicipalityResource($this->whenLoaded('municipality')),
            'interesting_places' => InterestingPlaceResource::collection($this->whenLoaded('interestingPlaces')),
            'meetings' => MeetingResource::collection($this->whenLoaded('meetings')),
        ];
    }
}

