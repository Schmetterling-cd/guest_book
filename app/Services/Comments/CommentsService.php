<?php

namespace App\Services\Comments;

use App\Contracts\Comments\CommentsInterface;
use App\Models\CommentModel;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;


class CommentsService extends Service implements CommentsInterface
{

    public function getComment(): array|false
    {

        $model = $this->getModel(new CommentModel());
        if (!$model) {
            return false;
        }

        $comment = $model->getComment($this->_data);
        if (!$comment) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        $user = $this->getUser();
        if ($comment->user_id != $user->id && !$user->hasRole('super-user')) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return ['commentId' => $comment->id, 'commentText' => $comment->comment_text, 'responses' => $comment->responses];

    }

    public function addComment(): int|bool
    {

        $model = $this->getModel(new CommentModel());
        if (!$model) {
            return false;
        }

        $user = $this->getUser();
        $comment = $model::create([
            'comment_text' => $this->_data['commentText'] ?? '',
            'user_id' => $user->id,
        ]);

        if (empty($comment)) {
            $this->setError('Не удалось создать запись.');
            return false;
        }

        return $comment->id;

    }

    public function updateComment(): int|bool
    {

        $model = $this->getModel(new CommentModel());
        if (!$model) {
            return false;
        }

        $comment = $model::find($this->_data['commentId']);
        if (empty($comment)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        $user = $this->getUser();
        if ($comment->user_id != $user->id && !$user->hasRole('super-user')) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        $comment->comment_text = $params['commentText'] ?? '';
        if ($comment->isDirty()) {
            $comment->save();
        }

        return $comment->id;

    }

    public function deleteComment(): bool
    {
        $model = $this->getModel(new CommentModel());
        if (!$model) {
            return false;
        }

        $comment = $model::find($this->_data['commentId']);
        if (empty($comment)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        $user = $this->getUser();
        if ($comment->user_id != $user->id && !$user->hasRole('super-user')) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return $model->deleteComment($comment);

    }

    public function getCommentList(): LengthAwarePaginator|bool
    {

        $model = $this->getModel(new CommentModel());
        if (!$model) {
            return false;
        }

        $list = $model->getCommentList();
        if ($list === false) {
            $this->setError('Не удалось получить список.');
            return false;
        }

        foreach ($list->all() as &$comment) {
            $responsesList = json_decode($comment->responses, true);

            $responses = [];
            foreach ($responsesList as $response) {
                if (empty($response) || empty($response['responseId']) || empty($response['responseText'])) {
                    continue;
                }

                $responses[] = $response;
            }

            $comment->responses = $responses;
        }

        return $list;

    }

}
