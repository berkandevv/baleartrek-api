<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'day' => $this->day,
            'hour' => $this->hour,
            'appDateIni' => $this->appDateIni,
            'appDateEnd' => $this->appDateEnd,
            'score' => [
                'total' => $this->totalScore,
                'count' => $this->countScore,
                'average' => $this->countScore > 0
                    ? round($this->totalScore / $this->countScore, 2)
                    : null,
            ],
            'guide' => new UserSummaryResource($this->whenLoaded('user')),
            'attendees' => UserSummaryResource::collection($this->whenLoaded('users')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}

