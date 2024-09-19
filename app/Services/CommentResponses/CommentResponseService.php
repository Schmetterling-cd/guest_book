<?php

namespace App\Services\CommentResponses;

use App\Contracts\CommentResponses\CommentResponsesInterface;
use App\Http\Resources\CommentResponseResource;
use App\Models\CommentResponse;
use App\Services\Service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;


class CommentResponseService extends Service implements CommentResponsesInterface
{

    protected const LIST_CACHE_KEY = 'comment_response';

    public function addResponseToComment(): int|bool
    {

        $model = $this->getModel(new CommentResponse());
        if (!$model) {
            return false;
        }

        if (Gate::inspect('create')->denied()) {
            $this->setError('У вас нет прав для создания записис.');
            return false;
        }

        $response = $model->create([
                'response_text' => $this->_data['responseText'] ?? '',
                'user_id' => $this->getUser()->id,
            ])
            ->commentsToCommentResponses()->create([
                'comment_id' => $this->_data['commentId'],
            ]);

        if (empty($response)) {
            $this->setError('Не удалось создать запись.');
            return false;
        }

        return $response->comment_response_id;

    }

    public function getResponseToComment(): JsonResource|bool
    {

        $model = $this->getModel(new CommentResponse());
        if (!$model) {
            return false;
        }

        $response = $model::with('comment')->find($this->_data['responseId']);
        if (empty($response)) {
            $this->setError('Запись не найдена.');
            return false;
        }

        if (Gate::inspect('get', $response)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return new CommentResponseResource($response);

    }

    public function updateResponseToComment(): int|bool
    {

        $model = $this->getModel(new CommentResponse());
        if (!$model) {
            return false;
        }

        $response = $model::find($this->_data['responseId']);
        if (empty($response)) {
            $this->setError('Запись не найдена.');
            return false;
        }

        if (Gate::inspect('update', $response)->denied()) {
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

        $model = $this->getModel(new CommentResponse());
        if (!$model) {
            return false;
        }

        $response = $model::find($this->_data['responseId']);
        if (empty($response)) {
            $this->setError('Запись не найдена.');
            return false;
        }

        if (Gate::inspect('delete', $response)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return $response->commentsToCommentResponses()->delete() && $response->delete();

    }

    public function getCommentResponseList(): AnonymousResourceCollection|bool
    {

        $user = $this->getUser();
        $cacheKey = static::LIST_CACHE_KEY . '_' . $user->id;

        if ($list = Cache::get($cacheKey)) {
            return $list;
        }

        $model = $this->getModel(new CommentResponse());
        if (!$model) {
            return false;
        }

        $list = $model->with(['comment'])->where('user_id', '=', $this->getUser()->id)->paginate(50);
        if ($list === false) {
            $this->setError('Не удалось получить список.');
            return false;
        }

        $list = CommentResponseResource::collection($list);

        Cache::add($cacheKey, $list, static::CACHE_EXPIRATION_TIME);

        return $list;

    }

}
