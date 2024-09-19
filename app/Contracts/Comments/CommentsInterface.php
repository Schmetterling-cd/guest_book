<?php

namespace App\Contracts\Comments;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface CommentsInterface
{

    public function getComment(): JsonResource|bool;
    public function addComment(): int|bool;
    public function updateComment(): int|bool;
    public function deleteComment(): bool;
    public function getCommentList(): AnonymousResourceCollection|bool;

}
