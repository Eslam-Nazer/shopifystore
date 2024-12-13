<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Public routes
Route::post("/login", [UserAuthController::class, "login"])->name('login');
Route::post(uri: '/register', action: [UserAuthController::class, 'register'])->name('register');

// Protected routes
Route::group(['middleware' => "auth:sanctum"], function () {
    Route::post("/logout", [UserAuthController::class, "logout"])->name('logout');
});
