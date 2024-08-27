<?php

namespace App\Http\Controllers\ApiControllers;

use App\Contracts\CommentResponses\CommentResponsesInterface;
use App\Contracts\Comments\CommentsInterface;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuestBookController extends Controller
{

    /**
     * Добавление ответа на комментарий
     *
     * @throws Exception
     */
    public function addResponseToComment(Request $request, CommentResponsesInterface $service): array
    {

        $service->setData(
            $request->validate([
                'commentId' => ['required', 'string'],
                'responseText' => ['required', 'string', 'min:1', 'max:255'],
            ])
        );

        $responseId = $service->addResponseToComment();
        if (empty($responseId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['responseId' => $responseId]);

    }

    /**
     * Получение ответа на комментарий с самим комментарием
     */
    public function getResponseToComment(Request $request, CommentResponsesInterface $service): array
    {

        $service->setData(
            $request->validate([
                'responseId' => ['required', 'string'],
            ])
        );

        $response = $service->getResponseToComment();
        if (empty($response)) {
            return $service->getApiError();
        }

        return $service->getApiResponse($response);

    }

    /**
     * Изменение комментария
     */
    public function updateResponseToComment(Request $request, CommentResponsesInterface $service): array
    {

        $service->setData(
            $request->validate([
                'responseId' => ['required', 'string'],
                'responseText' => ['required', 'string', 'min:1', 'max:255'],
            ])
        );

        $responseId = $service->updateResponseToComment();
        if (empty($response)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['responseId' => $responseId]);

    }

    /**
     * Удаление ответа на комментарий
     *
     * @throws Exception
     */
    public function deleteResponseToComment(Request $request, CommentResponsesInterface $service): array
    {

        $service->setData(
            $request->validate([
                'responseId' => ['required', 'string'],
            ])
        );

        if (!$service->deleteResponseToComment()) {
            return $service->getApiError();
        }

        return $service->getApiResponse();

    }

    /**
     * Получить список ответов с комментарием
     */
    public function getCommentResponseList(CommentResponsesInterface $service): array
    {

        $list = $service->getCommentResponseList();
        if (empty($list)) {
            return $service->getApiError();
        }

        return $service->getApiResponse($list);

    }

    /**
     * Получение комментария
     */
    public function getComment(Request $request, CommentsInterface $service): array
    {

        $service->setData(
            $request->validate([
                'commentId' => ['required', 'string'],
            ])
        );

        $comment = $service->getComment();
        if (empty($comment)) {
            return $service->getApiError();
        }

        return $service->getApiResponse($comment);

    }

    /**
     * Добавление комментария
     */
    public function addComment(Request $request, CommentsInterface $service): array
    {

        $service->setData(
            $request->validate([
                'commentText' => ['required', 'string', 'min:1', 'max:255'],
            ])
        );+

        $commentId = $service->addComment();
        if (empty($commentId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['commentId' => $commentId]);

    }

    /**
     * Изменение комментария
     */
    public function updateComment(Request $request, CommentsInterface $service): array
    {

        $service->setData(
            $request->validate([
                'commentId' => ['required', 'string'],
                'commentText' => ['required', 'string', 'min:1', 'max:255'],
            ])
        );

        $commentId = $service->updateComment();
        if (empty($commentId)) {
            return $service->getApiError();
        }

        return $service->getApiResponse(['commentId' => $commentId]);

    }

    /**
     * Удаление комментария
     */
    public function deleteComment(Request $request, CommentsInterface $service): array
    {

        $service->setData(
            $request->validate([
                'commentId' => ['required', 'string'],
            ])
        );

        if (!$service->deleteComment()) {
            return $service->getApiError();
        }

        return $service->getApiResponse();

    }

    /**
     * Получить список комментариев
     *
     * @throws Exception
     */
    public function getCommentList(CommentsInterface $service): array
    {

        $list = $service->getCommentList();
        if (empty($list)) {
            return $service->getApiError();
        }

        return $service->getApiResponse($list);

    }

}
