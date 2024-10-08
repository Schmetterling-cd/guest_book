<?php

namespace App\Providers;

use App\Contracts\Administration\Permission\PermissionInterface;
use App\Contracts\Administration\Role\RoleInterface;
use App\Contracts\CommentResponses\CommentResponsesInterface;
use App\Contracts\Comments\CommentsInterface;
use App\Services\Administration\Permission\PermissionService;
use App\Services\Administration\Role\RoleService;
use App\Services\CommentResponses\CommentResponseService;
use App\Services\Comments\CommentsService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(CommentsInterface::class, function ($app) {
            return new CommentsService();
        });

        $this->app->bind(CommentResponsesInterface::class, function ($app) {
            return new CommentResponseService();
        });

        $this->app->bind(RoleInterface::class, function ($app) {
            return new RoleService();
        });

        $this->app->bind(PermissionInterface::class, function ($app) {
            return new PermissionService();
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-user')) {
                return true;
            }
        });

    }
}
