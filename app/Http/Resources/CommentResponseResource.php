<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'commentResponseId' => (string) $this->id,
            'commentResponseText' => $this->response_text,
            'commentId' => (string) $this->comment->id,
            'createdAt' => $this->created_at,
            'isChanged' => date_create($this->created_at) !== date_create($this->updated_at)
        ];
    }
}
