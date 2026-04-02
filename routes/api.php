<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Route
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:20,1');

// Protected Routes (Require Token)
Route::middleware(['auth:sanctum','throttle:20,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
