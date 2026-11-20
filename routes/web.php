<?php

use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TasksController::class, 'index']);
Route::get('/tasks', [TasksController::class, 'create'])->middleware();
Route::post('/tasks', [TasksController::class, 'store'])->middleware();
Route::delete('/tasks/{task}', [TasksController::class, 'destroy'])->middleware();
Route::patch('/tasks/{task}/complete', [TasksController::class, 'complete'])->middleware();
Route::put('/tasks/{task}', [TasksController::class, 'update'])->middleware();
Route::patch('/tasks/{task}/progress', [TasksController::class, 'progress'])->middleware();



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::redirect('/dashboard','/');
});
