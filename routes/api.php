<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// User routes.
Route::apiResource('/users', UserController::class);
Route::post('/users/signup', [UserController::class, 'signup']);
Route::post('/users/login', [UserController::class, 'login']);
