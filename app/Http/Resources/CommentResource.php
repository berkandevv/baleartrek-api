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
        $imageUrl = null;

        if ($this->relationLoaded('images')) {
            $imageUrl = $this->images->first()?->url;
        }

        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'score' => $this->score,
            'status' => $this->status,
            'commentDate' => $this->created_at,
            'imageUrl' => is_string($imageUrl) ? ltrim($imageUrl, '/') : null,
            'user' => new UserSummaryResource($this->whenLoaded('user')),
        ];
    }
}
