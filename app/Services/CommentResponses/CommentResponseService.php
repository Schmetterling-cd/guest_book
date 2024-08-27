<?php

namespace App\Services\CommentResponses;

use App\Contracts\CommentResponses\CommentResponsesInterface;
use App\Models\CommentResponseModel;
use App\Services\Service;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentResponseService extends Service implements CommentResponsesInterface
{

    public function addResponseToComment(): int|bool
    {

        $model = $this->getModel(new CommentResponseModel());
        if (!$model) {
            return false;
        }

        $response = $model->addResponseToComment($this->_data);
        if (empty($response)) {
            $this->setError('Не удалось создать запись.');
            return false;
        }

        return $response->id;

    }

    public function getResponseToComment(): array|bool
    {

        $model = $this->getModel(new CommentResponseModel());
        if (!$model) {
            return false;
        }

        $response = $model::find($this->_data['responseId']);
        if (empty($response)) {
            $this->setError('Запись не найдена.');
            return false;
        }

        $user = $this->getUser();
        if ($response->user_id != $user->id && !$user->hasRole('super-user')) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        $response = $model->getResponseToComment([
            'responseId' => $this->_data['responseId'],
            'userId' => $user->id,
        ]);

        if (empty($response)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        return $response;

    }

    public function updateResponseToComment(): int|bool
    {

        $model = $this->getModel(new CommentResponseModel());
        if (!$model) {
            return false;
        }

        $response = $model::find($this->_data['responseId']);
        if (empty($response)) {
            $this->setError('Запись не найдена.');
            return false;
        }

        $user = $this->getUser();
        if ($response->user_id != $user->id && !$user->hasRole('super-user')) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        $response->response_text = $this->_data['responseText'];

        if ($response->isDirty()) {
            $response->save();
        }

        return $response->id;

    }

    public function deleteResponseToComment(): bool
    {

        $model = $this->getModel(new CommentResponseModel());
        if (!$model) {
            return false;
        }

        $response = $model::find($this->_data['responseId']);
        if (empty($response)) {
            $this->setError('Запись не найдена.');
            return false;
        }

        $user = $this->getUser();
        if ($response->user_id != $user->id && !$user->hasRole('super-user')) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return $model->deleteResponseToComment($response);

    }

    public function getCommentResponseList(): LengthAwarePaginator|bool
    {

        $model = $this->getModel(new CommentResponseModel());
        if (!$model) {
            return false;
        }

        $list = $model->getCommentResponseList($this->getUser()->id);
        if ($list === false) {
            $this->setError('Не удалось получить список.');
            return false;
        }

        return $list;

    }
}
