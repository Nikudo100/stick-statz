<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ClusterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClusterController::class, 'index'])->name('main');

Route::get('/clusters/{slug}', [ClusterController::class, 'show'])->name('clusters.show');

Route::get('/stocks', [StockController::class, 'show'])->name('stocks.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
