<?php

namespace App\Http\Controllers\ApiControllers;

use App\Models\CommentModel;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommentResponseModel;

class GuestBookController extends Controller
{

    /**
     * Добавление ответа на комментарий
     *
     * @throws Exception
     */
    public function addResponseToComment(Request $request): array
    {

        $validated = $request->validate([
            'commentId' => ['required', 'string'],
            'responseText' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        return $this->getApiResponse((new CommentResponseModel())->addResponseToComment($validated));

    }

    /**
     * Получение ответа на комментарий с самим комментарием
     *
     * @throws Exception
     */
        public function getResponseToComment(Request $request): array
        {

        $validated = $request->validate([
            'responseId' => ['required', 'string'],
        ]);

        $model = new CommentResponseModel();
        $response = $model->getResponseToComment($validated);

        if (!$response) {
            return $this->getApiResponse(false, $model->getError());
        }

        return $this->getApiResponse($response);

    }

    /**
     * Изменение комментария
     *
     * @throws Exception
     */
    public function updateResponseToComment(Request $request): array
    {

        $validated = $request->validate([
            'responseId' => ['required', 'string'],
            'responseText' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $model = new CommentResponseModel();
        $response = $model->updateResponseToComment($validated);

        if (!$response) {
            return $this->getApiResponse(false, $model->getError());
        }

        return $this->getApiResponse($response);

    }

    /**
     * Удаление ответа на комментарий
     *
     * @throws Exception
     */
    public function deleteResponseToComment(Request $request): array
    {

        $validated = $request->validate([
            'responseId' => ['required', 'string'],
        ]);

        $model = new CommentResponseModel();
        $response = $model->deleteResponseToComment($validated);

        if (!$response) {
            return $this->getApiResponse(false, $model->getError());
        }

        return $this->getApiResponse($response);

    }

    /**
     * Получить список ответов с комментарием
     *
     * @throws Exception
     */
    public function getCommentResponseList(): array
    {

        return $this->getApiResponse((new CommentResponseModel())->getCommentResponseList());

    }

    /**
     * Получение еомментария
     *
     * @throws Exception
     */
    public function getComment(Request $request): array
    {

        $validated = $request->validate([
            'commentId' => ['required', 'string'],
        ]);

        $model = new CommentModel();
        $comment = $model->getComment($validated);

        if (!$comment) {
            return $this->getApiResponse(false, $model->getError());
        }

        return $this->getApiResponse($comment);

    }

    /**
     * Добавление комментария
     *
     * @throws Exception
     */
    public function addComment(Request $request): array
    {

        $validated = $request->validate([
            'commentText' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        return $this->getApiResponse((new CommentModel())->addComment($validated));

    }

    /**
     * Изменение комментария
     *
     * @throws Exception
     */
    public function updateComment(Request $request): array
    {

        $validated = $request->validate([
            'commentId' => ['required', 'string'],
            'commentText' => ['required', 'string', 'min:1', 'max:255'],
        ]);

        $model = new CommentModel();
        $commentId = $model->updateComment($validated);

        if (!$commentId) {
            return $this->getApiResponse(false, $model->getError());
        }

        return $this->getApiResponse($commentId);

    }

    /**
     * Удаление комментария
     *
     * @throws Exception
     */
    public function deleteComment(Request $request): array
    {

        $validated = $request->validate([
            'commentId' => ['required', 'string'],
        ]);

        $model = new CommentModel();

        if (!$model->deleteComment($validated)) {
            return $this->getApiResponse(false, $model->getError());
        }

        return $this->getApiResponse(true);

    }

    /**
     * Получить список комментариев
     *
     * @throws Exception
     */
    public function getCommentList(): array
    {

        return $this->getApiResponse((new CommentModel())->getCommentList());

    }

}
