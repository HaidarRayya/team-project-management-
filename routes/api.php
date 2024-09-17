<?php

use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Employee\NoteController;
use App\Http\Controllers\Employee\ProjectController as EmployeeProjectController;
use App\Http\Controllers\Employee\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('register', 'register');
});
Route::prefix('admin')->middleware(['is_admin', 'auth:api'])->group(function () {
    Route::apiResource('users', UserController::class)->except(['update', 'create']);
    Route::get('/allDeletedUsers', [UserController::class, 'allDeletedUsers']);
    Route::get('/users/{user}/restoreUser', [UserController::class, 'allDeletedUsers']);
    Route::apiResource('projects', AdminProjectController::class);
    Route::post('/projects/{project}/appointEmployee/{user}', [AdminProjectController::class, 'appointEmployee']);
    Route::post('/projects/{project}/removeEmployee/{user}', [AdminProjectController::class, 'removeEmployee']);
});


Route::prefix('employee')->middleware(['is_employee', 'auth:api'])->group(function () {
    Route::apiResource('projects', EmployeeProjectController::class)->only(['index', 'show']);
    Route::post('/projects/{project}/endProject', [EmployeeProjectController::class, 'endProject']);
    Route::apiResource('projects.tasks',  TaskController::class);
    Route::post('/projects/{project}/tasks/{task}/startWorkTask', [TaskController::class, 'startWorkTask']);
    Route::post('/projects/{project}/tasks/{task}/endWorkTask', [TaskController::class, 'endWorkTask']);
    Route::post('/projects/{project}/tasks/{task}/startTestTask', [TaskController::class, 'startTestTask']);
    Route::post('/projects/{project}/tasks/{task}/endTestTask', [TaskController::class, 'endTestTask']);
    Route::post('/projects/{project}/tasks/{task}/endTask', [TaskController::class, 'endTask']);
    Route::apiResource('projects.tasks.notes', NoteController::class);
});
