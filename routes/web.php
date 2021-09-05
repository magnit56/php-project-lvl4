<?php

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
});

Route::get('task_statuses', [TaskStatusController::class, 'index'])
    ->name('taskStatus.index');
//Route::get('task_statuses/{id}', [TaskStatusController::class, 'show'])
//    ->name('taskStatus.show');
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




Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
