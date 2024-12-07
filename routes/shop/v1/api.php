<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserAuthController;

/* login users */

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get(uri: '/login', action: [UserAuthController::class, 'login'])->name('login');
Route::post(uri: '/login', action: [UserAuthController::class, 'authenticate']);

/* Register users */

Route::get(uri: '/signup', action: [UserAuthController::class, 'sginup'])->name('signup');
Route::post(uri: '/signup', action: [UserAuthController::class, 'register'])->name('register');
