<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskStatusController;
use Illuminate\Contracts\View\View;

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

Route::get('/', function (): View {
    return view('index');
});

// @phpstan-ignore-next-line
Route::get('task_statuses', [TaskStatusController::class, 'index'])
    ->name('taskStatus.index');
// @phpstan-ignore-next-line
Route::get('task_statuses/create', [TaskStatusController::class, 'create'])
    ->middleware('auth')
    ->name('taskStatus.create');
// @phpstan-ignore-next-line
Route::post('task_statuses', [TaskStatusController::class, 'store'])
    ->middleware('auth')
    ->name('taskStatus.store');
// @phpstan-ignore-next-line
Route::delete('task_statuses/{id}', [TaskStatusController::class, 'destroy'])
    ->middleware('auth')
    ->name('taskStatus.destroy');
// @phpstan-ignore-next-line
Route::get('task_statuses/{id}/edit', [TaskStatusController::class, 'edit'])
    ->middleware('auth')
    ->name('taskStatus.edit');
// @phpstan-ignore-next-line
Route::patch('task_statuses/{id}', [TaskStatusController::class, 'update'])
    ->middleware('auth')
    ->name('taskStatus.update');
// @phpstan-ignore-next-line
Route::get('task_statuses/{id}', [TaskStatusController::class, 'show'])
    ->middleware('auth')
    ->name('taskStatus.show');

// @phpstan-ignore-next-line
Route::get('tasks', [TaskController::class, 'index'])
    ->name('task.index');
// @phpstan-ignore-next-line
Route::get('tasks/create', [TaskController::class, 'create'])
    ->middleware('auth')
    ->name('task.create');
// @phpstan-ignore-next-line
Route::post('tasks', [TaskController::class, 'store'])
    ->middleware('auth')
    ->name('task.store');
// @phpstan-ignore-next-line
Route::delete('tasks/{id}', [TaskController::class, 'destroy'])
    ->middleware('auth')
    ->name('task.destroy');
// @phpstan-ignore-next-line
Route::get('tasks/{id}/edit', [TaskController::class, 'edit'])
    ->middleware('auth')
    ->name('task.edit');
// @phpstan-ignore-next-line
Route::patch('tasks/{id}', [TaskController::class, 'update'])
    ->middleware('auth')
    ->name('task.update');
// @phpstan-ignore-next-line
Route::get('tasks/{id}', [TaskController::class, 'show'])
    ->middleware('auth')
    ->name('task.show');

// @phpstan-ignore-next-line
Route::get('labels', [LabelController::class, 'index'])
    ->name('label.index');
// @phpstan-ignore-next-line
Route::get('labels/create', [LabelController::class, 'create'])
    ->middleware('auth')
    ->name('label.create');
// @phpstan-ignore-next-line
Route::post('labels', [LabelController::class, 'store'])
    ->middleware('auth')
    ->name('label.store');
// @phpstan-ignore-next-line
Route::delete('labels/{id}', [LabelController::class, 'destroy'])
    ->middleware('auth')
    ->name('label.destroy');
// @phpstan-ignore-next-line
Route::get('labels/{id}/edit', [LabelController::class, 'edit'])
    ->middleware('auth')
    ->name('label.edit');
// @phpstan-ignore-next-line
Route::patch('labels/{id}', [LabelController::class, 'update'])
    ->middleware('auth')
    ->name('label.update');
// @phpstan-ignore-next-line
Route::get('labels/{id}', [LabelController::class, 'show'])
    ->middleware('auth')
    ->name('label.show');

Auth::routes();

// @phpstan-ignore-next-line
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
