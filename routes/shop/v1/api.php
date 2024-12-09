<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Public routes
Route::post("/login", [UserAuthController::class, "login"]);

/* Register users */

Route::get(uri: '/signup', action: [UserAuthController::class, 'sginup'])->name('signup');
Route::post(uri: '/signup', action: [UserAuthController::class, 'register'])->name('register');
