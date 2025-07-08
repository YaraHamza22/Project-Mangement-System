<?php

use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
 });

 Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index']);
    Route::get('/projects/{project}/tasks/completed-count', [TaskController::class, 'completedCount']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->prefix('teams')->controller(TeamController::class)->group(function(){
    Route::get('/','index');
    Route::post('/','store');
    Route::get('{team}','show');
    Route::put('{team}','update');
    Route::delete('{team}','delete');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
});



    //انشاء مهام مشروع معين
    //Route::middleware(['auth:sanctum'])->group(function () {
    //Route::prefix('projects/{project}/tasks')->name('projects.tasks.')->group(function () {
       // Route::get('/', [TaskController::class, 'getTasksForProject'])->name('index');
      ////  Route::post('/', [TaskController::class, 'storeForProject'])->name('store');
   // });

//});

Route::prefix('attachments')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [AttachmentController::class, 'index']);
    Route::post('/', [AttachmentController::class, 'store']);
    Route::get('{attachment}', [AttachmentController::class, 'show']);
    Route::put('{attachment}', [AttachmentController::class, 'update']);
    Route::delete('{attachment}', [AttachmentController::class, 'destroy']);
});
Route::prefix('comments')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::post('/', [CommentController::class, 'store']);
    Route::get('{comment}', [CommentController::class, 'show']);
    Route::put('{comment}', [CommentController::class, 'update']);
    Route::delete('{comment}', [CommentController::class, 'destroy']);
});




