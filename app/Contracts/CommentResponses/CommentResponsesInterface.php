<?php

namespace App\Contracts\CommentResponses;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

interface CommentResponsesInterface
{

    public function addResponseToComment(): int|bool;
    public function getResponseToComment(): JsonResource|bool;
    public function updateResponseToComment(): int|bool;
    public function deleteResponseToComment(): bool;
    public function getCommentResponseList(): AnonymousResourceCollection|bool;

}
