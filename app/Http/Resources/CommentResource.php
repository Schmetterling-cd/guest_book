<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'commentId' => (string) $this->id,
            'commentText' => $this->comment_text,
            'createdAt' => $this->created_at,
            'isChanged' => date_create($this->created_at) != date_create($this->updated_at),
            'commentResponses' => CommentResponseResource::collection($this->responses),
        ];
    }
}
