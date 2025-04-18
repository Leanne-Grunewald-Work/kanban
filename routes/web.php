<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::resource('boards', BoardController::class);

    Route::prefix('boards/{board}')->group(function () {
        Route::resource('columns', ColumnController::class)->except(['show']);
    });

    Route::prefix('boards/{board}/columns/{column}')->group(function () {
        Route::resource('tasks', TaskController::class)->names('tasks');
    });

    Route::prefix('boards/{board}/columns/{column}/tasks/{task}')->group(function () {
        Route::post('subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
        Route::patch('subtasks/{subtask}', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
        Route::patch('subtasks/{subtask}/edit', [SubtaskController::class, 'update'])->name('subtasks.update');
        Route::delete('subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
    });
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
