<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\AttachmentController;

// Authentication Routes with Rate Limiting
Route::prefix('auth')->group(function () {
    // 📌 د. Rate Limiting: 5 محاولات لكل دقيقة
    Route::post('register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('auth.register');

    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('auth.login');
});

// Protected Routes (Require Sanctum Authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Logout Route
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // Authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Teams
    Route::apiResource('teams', TeamController::class);

    // Projects
    Route::get('projects/top-active', [ProjectController::class, 'topActiveProjects'])->name('projects.top-active');
    Route::post('projects/{project}/assign-members', [ProjectController::class, 'assignMembers'])->name('projects.assign-members');
    Route::apiResource('projects', ProjectController::class);

    // Tasks
    Route::get('projects/{project}/tasks', [TaskController::class, 'index'])->name('projects.tasks.index');
    Route::get('projects/{project}/tasks/completed-count', [TaskController::class, 'completedCount'])->name('projects.tasks.completed-count');
    Route::apiResource('tasks', TaskController::class)->except('index');

    // Comments (no index/show because it's polymorphic)
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::put('{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });

    // Attachments
    Route::apiResource('attachments', AttachmentController::class);
});
