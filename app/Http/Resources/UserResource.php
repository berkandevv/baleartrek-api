<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'created_at' => $this->created_at,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'dni' => $this->dni,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->whenLoaded('role'),
            'meetings' => MeetingResource::collection($this->whenLoaded('meetings')),
            'meeting' => new MeetingResource($this->whenLoaded('meeting')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
