<?php

namespace App\Services\Comments;

use App\Contracts\Comments\CommentsInterface;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Services\Service;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentsService extends Service implements CommentsInterface
{

    protected const LIST_CACHE_KEY = 'comment_list';

    public function getComment(): JsonResource|bool
    {

        $model = $this->getModel(new Comment());
        if (!$model) {
            return false;
        }

        $comment = $model::with(['responses'])->find($this->_data['commentId']);
        if (!$comment) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        if (Gate::inspect('get', $comment)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return new CommentResource($comment);

    }

    public function addComment(): int|bool
    {

        $model = $this->getModel(new Comment());
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

        $model = $this->getModel(new Comment());
        if (!$model) {
            return false;
        }

        $comment = $model::find($this->_data['commentId']);
        if (empty($comment)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        if (Gate::inspect('update', $comment)->denied()) {
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
        $model = $this->getModel(new Comment());
        if (!$model) {
            return false;
        }

        $comment = $model::find($this->_data['commentId']);
        if (empty($comment)) {
            $this->setError('Не удалось получить запись.');
            return false;
        }

        if (Gate::inspect('delete', $comment)->denied()) {
            $this->setError('Данная запись не доступна текущему пользователю.');
            return false;
        }

        return $comment->delete();

    }

    public function getCommentList(): AnonymousResourceCollection|bool
    {

        $user = $this->getUser();
        $cacheKey = static::LIST_CACHE_KEY . '_' . $user->id;

        if ($list = Cache::get($cacheKey)) {
            return $list;
        }

        $model = $this->getModel(new Comment());
        if (!$model) {
            return false;
        }

        $list = $model->with(['responses'])->paginate(50);
        if ($list === null) {
            $this->setError('Не удалось получить список.');
            return false;
        }

        $list = CommentResource::collection($list);

        Cache::add($cacheKey, $list, static::CACHE_EXPIRATION_TIME);

        return $list;

    }

}
