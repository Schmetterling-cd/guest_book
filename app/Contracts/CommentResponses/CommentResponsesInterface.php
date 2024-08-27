<?php

namespace App\Contracts\CommentResponses;

use Illuminate\Pagination\LengthAwarePaginator;

interface CommentResponsesInterface
{

    public function addResponseToComment(): int|bool;
    public function getResponseToComment(): array|bool;
    public function updateResponseToComment(): int|bool;
    public function deleteResponseToComment(): bool;
    public function getCommentResponseList(): LengthAwarePaginator|bool;

}
