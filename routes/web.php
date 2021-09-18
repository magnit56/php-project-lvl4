<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskStatusController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})
    ->name('index');

Route::get('task_statuses', [TaskStatusController::class, 'index'])
    ->name('taskStatus.index');
Route::get('task_statuses/create', [TaskStatusController::class, 'create'])
    ->middleware('auth')
    ->name('taskStatus.create');
Route::post('task_statuses', [TaskStatusController::class, 'store'])
    ->middleware('auth')
    ->name('taskStatus.store');
Route::delete('task_statuses/{id}', [TaskStatusController::class, 'destroy'])
    ->middleware('auth')
    ->name('taskStatus.destroy');
Route::get('task_statuses/{id}/edit', [TaskStatusController::class, 'edit'])
    ->middleware('auth')
    ->name('taskStatus.edit');
Route::patch('task_statuses/{id}', [TaskStatusController::class, 'update'])
    ->middleware('auth')
    ->name('taskStatus.update');
Route::get('task_statuses/{id}', [TaskStatusController::class, 'show'])
    ->middleware('auth')
    ->name('taskStatus.show');

Route::get('tasks', [TaskController::class, 'index'])
    ->name('task.index');
Route::get('tasks/create', [TaskController::class, 'create'])
    ->middleware('auth')
    ->name('task.create');
Route::post('tasks', [TaskController::class, 'store'])
    ->middleware('auth')
    ->name('task.store');
Route::delete('tasks/{id}', [TaskController::class, 'destroy'])
    ->middleware('auth')
    ->name('task.destroy');
Route::get('tasks/{id}/edit', [TaskController::class, 'edit'])
    ->middleware('auth')
    ->name('task.edit');
Route::patch('tasks/{id}', [TaskController::class, 'update'])
    ->middleware('auth')
    ->name('task.update');
Route::get('tasks/{id}', [TaskController::class, 'show'])
    ->middleware('auth')
    ->name('task.show');

Route::get('labels', [LabelController::class, 'index'])
    ->name('label.index');
Route::get('labels/create', [LabelController::class, 'create'])
    ->middleware('auth')
    ->name('label.create');
Route::post('labels', [LabelController::class, 'store'])
    ->middleware('auth')
    ->name('label.store');
Route::delete('labels/{id}', [LabelController::class, 'destroy'])
    ->middleware('auth')
    ->name('label.destroy');
Route::get('labels/{id}/edit', [LabelController::class, 'edit'])
    ->middleware('auth')
    ->name('label.edit');
Route::patch('labels/{id}', [LabelController::class, 'update'])
    ->middleware('auth')
    ->name('label.update');
Route::get('labels/{id}', [LabelController::class, 'show'])
    ->middleware('auth')
    ->name('label.show');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
