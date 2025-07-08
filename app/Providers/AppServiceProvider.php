<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies =[
        Team::class => TeamPolicy::class,
        Task::class => TaskPolicy::class,
        Project::class => ProjectPolicy::class,
        Comment::class => CommentPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
   //
    }
}
