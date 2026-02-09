<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'score' => $this->score,
            'status' => $this->status,
            'user' => new UserSummaryResource($this->whenLoaded('user')),
            'image' => $this->whenLoaded('images', function () {
                $image = $this->images->first();

                if (! $image) {
                    return null;
                }

                return [
                    'id' => $image->id,
                    'url' => $image->url,
                ];
            }),
        ];
    }
}
