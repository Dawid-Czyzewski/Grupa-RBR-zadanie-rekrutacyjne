<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\PublicTaskController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        Route::post('/{task}/share', [TaskController::class, 'share'])->name('share');
        Route::get('/{task}/history', [TaskController::class, 'history'])->name('history');
    });
});

Route::get('/public/tasks/shared/{token}', [PublicTaskController::class, 'show'])->name('tasks.shared.show');
