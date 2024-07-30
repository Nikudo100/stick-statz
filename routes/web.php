<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AbcReportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TurnoverReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ClusterController::class, 'index'])->name('main');


Route::get('/stocks', [StockController::class, 'show'])->name('stocks.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/clusters/{slug}', [ClusterController::class, 'show'])->name('clusters.show');

    Route::get('/test', [TestController::class, 'index'])->name('test.index');

    Route::get('/abc', [AbcReportController::class, 'index'])->name('abc.index');

    Route::get('/turnover', [TurnoverReportController::class, 'index'])->name('turnover.index');

    Route::get('/stocks', [StockController::class, 'show'])->name('stocks.show');

    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::put('/feedbacks/{feedback}', [FeedbackController::class, 'update'])->name('feedbacks.update');
    Route::put('/feedbacks/{feedback}/auto-answer', [FeedbackController::class, 'autoAnswer'])->name('feedbacks.autoAnswer');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

});

require __DIR__ . '/auth.php';
