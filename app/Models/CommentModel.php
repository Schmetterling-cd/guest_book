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

            $comment->respponses = CommentResponseModel::select('response_text as responseText', 'comment_response_id as responseId')
                ->join('comments_to_comment_responses', 'comment_responses.id', '=', 'comments_to_comment_responses.comment_response_id')
                ->where('comments_to_comment_responses.comment_id', '=', $commentId)
                ->get();

            return $comment;

        } catch (Throwable $exception) {
            throw new Exception('Не удалось подготовить запись.', 500);
        }

    }

    public function deleteComment($comment)
    {

        try {
            return DB::transaction(function () use ($comment) {
                $responseData = DB::table('comments_to_comment_responses')
                    ->where('comment_id', $comment->id)
                    ->first();

                if (empty($responseData)) {
                    return (bool) $comment->delete();
                }

                $response = DB::table('comment_responses')->find($responseData->comment_response_id);

                return $responseData->delete() && $response->delete() && $comment->delete();

            });
        } catch (Throwable $exception) {
            return false;
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

            $commentList = static::select('comments.id as commentId', 'comments.comment_text as commentText', DB::raw('jsonb_build_array(jsonb_build_object( \'responseId\', responses.comment_response_id, \'responseText\', responses.response_text)) as responses'))
                                ->leftJoinSub($responses, 'responses', function (JoinClause $join) {
                                    $join->on('comments.id', '=', 'responses.comment_id');
                                })
                                ->groupBy('comments.id', 'responses.comment_response_id', 'responses.response_text');

            return $commentList->paginate(50);

        } catch (Throwable $exception) {
            throw new Exception('Не удалось получить список.', 500);
        }

    }

}
