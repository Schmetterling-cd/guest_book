<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CommentResponseModel extends BaseModel
{

    protected $table = 'comment_responses';

    use HasFactory;

    protected $fillable = [
        'id',
        'response_text',
        'user_id',
    ];

    /**
     * @throws Exception
     */
    public function addResponseToComment(array $params = array())
    {

        try {
            return DB::transaction(function () use ($params) {

                $response = static::createOrFail([
                    'response_text' => $params['responseText'] ?? '',
                    'user_id' => Auth::user()->id,
                ]);

                if (empty($response)) {
                    return new Exception();
                }

                DB::table('comments_to_comment_responses')->insert([
                    'comment_id' => $params['commentId'] ?? '',
                    'comment_response_id' => $response->id,
                ]);

                return ['responseId' => $response->id];

            });
        } catch (Throwable $exception) {
            throw new Exception('Не удалось создать запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function getResponseToComment(array $params = array()): object|null|bool
    {


        try {
            $response = static::find($params['responseId']);

            $user = Auth::user();
            if ($response->user_id != $user->id && !$user->hasRole('super-user')) {
                $this->setError('Данная запись не доступна текущему пользователю.');
                return false;
            }

            $response = static::select()
                ->join('comments_to_comment_responses', 'comment_responses.id', '=', 'comments_to_comment_responses.comment_response_id')
                ->where('comment_responses.id', $params['responseId'])
                ->where('comment_responses.user_id', Auth::user()->id);

            return DB::table('comments')
                ->select('response.comment_response_id as responseId', 'response.response_text as responseText', 'comments.id as commentId', 'comments.comment_text as commentText')
                ->joinSub($response, 'response', function (JoinClause $join) {
                    $join->on('comments.id', '=', 'response.comment_id');
                })->first();

        } catch (Throwable $exception) {
            throw new Exception('Не удалось получить запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function updateResponseToComment(array $params = array())
    {

        try {
            $response = static::find($params['responseId']);

            $user = Auth::user();
            if ($response->user_id != $user->id && !$user->hasRole('super-user')) {
                $this->setError('Данная запись не доступна текущему пользователю.');
                return false;
            }

            $response->response_text = $params['responseText'];

            if ($response->isDirty()) {
                $response->save();
            }

            return ['responseId' => $params['responseId']];

        } catch (Throwable $exception) {
            throw new Exception('Не удалось изменить запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function deleteResponseToComment(array $params = array())
    {

        try {
            $response = static::find($params['responseId']);

            $user = Auth::user();
            if ($response->user_id != $user->id && !$user->hasRole('super-user')) {
                $this->setError('Данная запись не доступна текущему пользователю.');
                return false;
            }

            return DB::transaction(function () use ($response, $params) {

                DB::table('comments_to_comment_responses')
                    ->where('comment_response_id', $params['responseId'])
                    ->delete();

                return (bool) $response->delete();

            });

        } catch (Throwable $exception) {
            throw new Exception('Не удалось удалить запись.', 500);
        }

    }

    /**
     * @throws Exception
     */
    public function getCommentResponseList(): LengthAwarePaginator
    {

        try {
            $response = static::select()
                ->join('comments_to_comment_responses', 'comment_responses.id', '=', 'comments_to_comment_responses.comment_response_id')
                ->where('comment_responses.user_id', Auth::user()->id);

            return DB::table('comments')
                ->select('response.comment_response_id as responseId', 'response.response_text as responseText', 'comments.id as commentId', 'comments.comment_text as commentText')
                ->joinSub($response, 'response', function (JoinClause $join) {
                    $join->on('comments.id', '=', 'response.comment_id');
                })->paginate(50);

        } catch (Throwable $exception) {
            throw new Exception('Не удалось получить запись.', 500);
        }

    }

}
