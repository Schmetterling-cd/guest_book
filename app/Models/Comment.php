<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Comment extends Model
{

    protected $table = 'comments';

    use HasFactory;

    protected $fillable = [
        'id',
        'comment_text',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'laravel_through_key',
    ];

    public function responses(): HasManyThrough
    {

        return $this->hasManyThrough(
            CommentResponse::class,
            CommentsToCommentResponses::class,
            'comment_id',
            'id',
            'id',
            'comment_response_id'
        );

    }

    public function commentsToCommentResponses(): HasMany
    {

        return $this->hasMany(CommentsToCommentResponses::class, 'comment_id', 'id');

    }

}
