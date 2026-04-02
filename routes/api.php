<?php

use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public Route
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
// This provides the name "password.setup" for the URL generator
// The GET route for the email button
Route::get('/password/setup/{user}', [AuthController::class, 'showPasswordSetupForm'])
    ->name('password.setup');

// The POST route for the actual form submission
Route::post('/password/setup/{user}', [AuthController::class, 'setupPassword'])
    ->name('password.update.signed');

Route::get('/password/verify/{user}', [AuthController::class, 'verifySignature'])
    ->name('password.verify');

// Protected Routes (Require Token)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('throttle:10,1');
    Route::post('/admin/users/add', [UserController::class, 'store']);
    
    // Route to fetch all committee members for the accounts list
    Route::get('/admin/users', [UserController::class, 'index']);

    Route::post('/admin/users/{user}/resend-invite', [UserController::class, 'resendInvite']);

    //Collection routes
    Route::get('/admin/collections', [CollectionController::class, 'index']);
});
