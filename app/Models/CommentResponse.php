<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class CommentResponse extends Model
{

    protected $table = 'comment_responses';

    use HasFactory;

    protected $fillable = [
        'id',
        'response_text',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'laravel_through_key',
    ];

    public function comment(): HasOneThrough
    {

        return $this->hasOneThrough(
            Comment::class,
            CommentsToCommentResponses::class,
            'comment_response_id',
            'id',
            'id',
            'comment_id'
        );

    }

    public function commentsToCommentResponses(): HasOne
    {

        return $this->hasOne(CommentsToCommentResponses::class, 'comment_response_id', 'id');

    }

}
