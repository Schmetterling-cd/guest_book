<?php

namespace App\Http\Resources\User\Owner;

use App\Http\Resources\CommentResponseResource;
use App\Http\Resources\RoleResource;
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
            'userId' => (string) $this->id,
            'login' => $this->login,
			'roles' => RoleResource::collection($this->roles),
        ];
    }
}
