<?php

namespace App\Contracts\Comments;

use Illuminate\Pagination\LengthAwarePaginator;

interface CommentsInterface
{

    public function getComment(): array|bool;
    public function addComment(): int|bool;
    public function updateComment(): int|bool;
    public function deleteComment(): bool;
    public function getCommentList(): LengthAwarePaginator|bool;

}
