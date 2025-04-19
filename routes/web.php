<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (requires authentication and email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated user routes
Route::middleware(['auth'])->group(function () {
    
    // Board routes
    Route::get('/boards', [BoardController::class, 'index'])->name('boards.index');
    Route::get('/boards/{board}', [BoardController::class, 'show'])->name('boards.show');
    Route::resource('boards', BoardController::class)->except(['index', 'show']);

    // Nested routes under a specific board
    Route::prefix('boards/{board}')->group(function () {
        
        // Column routes
        Route::resource('columns', ColumnController::class)->except(['show']);
        
        // Nested routes under a specific column
        Route::prefix('columns/{column}')->group(function () {
            
            // Task routes
            Route::resource('tasks', TaskController::class);

            // Nested routes under a specific task
            Route::prefix('tasks/{task}')->group(function () {
                Route::get('subtasks', [SubtaskController::class, 'list'])->name('subtasks.list');
                Route::post('subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
                Route::patch('subtasks/{subtask}/edit', [SubtaskController::class, 'update'])->name('subtasks.update');
                Route::patch('subtasks/{subtask}', [SubtaskController::class, 'toggle'])->name('subtasks.toggle');
                Route::delete('subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
            });
        });
    });

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';
