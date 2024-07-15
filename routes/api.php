<?php

use Illuminate\Http\Request;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
Route::get('/test', [TestController::class, 'index'])->middleware('role:User');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
