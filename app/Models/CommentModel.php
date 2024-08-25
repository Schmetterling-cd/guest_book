<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentModel extends BaseModel
{

    protected $table = 'comments';

    use HasFactory;

    protected $fillable = [
        'comment_text',
        'user_id',
    ];

    /**
     * @throws Exception
     */
    public function getComment(array $params = array())
    {

        try {

            $commentId = $params['commentId'] ?? '';
            $comment = static::find($commentId);

            $user = Auth::user();
            if ($comment->user_id != $user->id && !$user->hasRole('super-user')) {
                $this->setError('Данная запись не доступна текущему пользователю.');
                return false;
            }

            $responses = CommentResponseModel::select('response_text as responseText', 'comment_response_id as responseId')
                ->join('comments_to_comment_responses', 'comment_responses.id', '=', 'comments_to_comment_responses.comment_response_id')
                ->where('comments_to_comment_responses.comment_id', '=', $commentId)
                ->get();

            if (empty($responses))
            {
                return ['commentId' => $comment->id, 'commentText' => $comment->comment_text];
            }

            return ['commentId' => $comment->id, 'commentText' => $comment->comment_text, 'responses' => $responses];

        } catch (Throwable $exception) {
            throw new Exception('Не удалось подготовить запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function addComment(array $params = array()): array
    {

        try {
            $comment = static::create([
                'comment_text' => $params['commentText'] ?? '',
                'user_id' => Auth::user()->id,
            ]);

            return ['commentId' => $comment->id];
        } catch (Throwable $exception) {
            throw new Exception('Не удалось создать запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function updateComment(array $params = array()): array|bool
    {

        try {
            $commentId = $params['commentId'] ?? '';
            $comment = static::find($commentId);

            $user = Auth::user();
            if ($comment->user_id != $user->id && !$user->hasRole('super-user')) {
                $this->setError('Данная запись не доступна текущему пользователю.');
                return false;
            }

            $comment->comment_text = $params['commentText'] ?? '';

            if ($comment->isDirty()) {
                $comment->save();
            }

            return ['commentId' => $commentId];

        } catch (Throwable $exception) {
            throw new Exception('Не удалось изменить запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function deleteComment(array $params = array())
    {

        try {
            $commentId = $params['commentId'] ?? '';
            $comment = static::find($commentId);

            $user = Auth::user();
            if ($comment->user_id != $user->id && !$user->hasRole('super-user')) {
                $this->setError('Данная запись не доступна текущему пользователю.');
                return false;
            }

            return DB::transaction(function () use ($comment, $commentId) {

                $responseData = DB::table('comments_to_comment_responses')
                    ->where('comment_id', $commentId)
                    ->first();

                if (empty($responseData)) {
                    return (bool) $comment->delete();
                }

                $response = DB::table('comment_responses')->find($responseData->comment_response_id);

                return $responseData->delete() && $response->delete() && $comment->delete();

            });
        } catch (Throwable $exception) {
            throw new Exception('Не удалось удалить запись.', 400);
        }

    }

    /**
     * @throws Exception
     */
    public function getCommentList()
    {

        try {

            $responses = DB::table('comments_to_comment_responses')
                                ->select()
                                ->join('comment_responses', 'comments_to_comment_responses.comment_response_id', '=', 'comment_responses.id');

            $commentList = static::select('comments.id as commentId', 'comments.comment_text as commentText', DB::raw('jsonb_object_agg(coalesce(responses.comment_response_id, \'0\'), responses.response_text) as responses'))
                                ->leftJoinSub($responses, 'responses', function (JoinClause $join) {
                                    $join->on('comments.id', '=', 'responses.comment_id');
                                })
                                ->groupBy('comments.id');

            $commentList = $commentList->paginate(50);

            foreach ($commentList->all() as &$comment) {
                $responsesList = json_decode($comment->responses, true);

                $responses = [];
                foreach ($responsesList as $key => $response) {
                    if (empty($response)) {
                        continue;
                    }

                    $responses[] = [
                        'responseId' => $key,
                        'responseText' => $response,
                    ];
                }

                $comment->responses = $responses;
            }

            return $commentList;

        } catch (Throwable $exception) {
            throw new Exception('Не удалось получить список.', 500);
        }

    }

}
