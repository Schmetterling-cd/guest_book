<?php

use App\Models\Comment;
use App\Models\CommentResponse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments_to_comment_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Comment::class);
            $table->foreignIdFor(CommentResponse::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments_to_comment_responses');
    }
};
