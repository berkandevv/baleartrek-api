<?php

namespace App\Http\Resources;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $enrollmentDates = $this->day
            ? Meeting::enrollmentDatesForDay($this->day)
            : [
                'appDateIni' => $this->appDateIni,
                'appDateEnd' => $this->appDateEnd,
            ];

        $extraGuides = collect();
        $attendees = collect();

        if ($this->relationLoaded('users')) {
            $participants = $this->users;
            $guideRoleId = $this->relationLoaded('user') ? $this->user?->role_id : null;

            if ($guideRoleId !== null) {
                $extraGuides = $participants->where('role_id', $guideRoleId)->values();
                $attendees = $participants->where('role_id', '!=', $guideRoleId)->values();
            } else {
                $attendees = $participants->values();
            }
        }

        return [
            'id' => $this->id,
            'day' => $this->day,
            'hour' => $this->hour,
            'appDateIni' => $enrollmentDates['appDateIni'],
            'appDateEnd' => $enrollmentDates['appDateEnd'],
            'trek_name' => $this->whenLoaded('trek', function () {
                return $this->trek?->name;
            }),
            'score' => [
                'total' => $this->totalScore,
                'count' => $this->countScore,
                'average' => $this->countScore > 0
                    ? round($this->totalScore / $this->countScore, 2)
                    : null,
            ],
            'guide' => new UserSummaryResource($this->whenLoaded('user')),
            'extraGuides' => $this->when(
                $this->relationLoaded('users'),
                fn () => UserSummaryResource::collection($extraGuides)
            ),
            'attendees' => $this->when(
                $this->relationLoaded('users'),
                fn () => UserSummaryResource::collection($attendees)
            ),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
