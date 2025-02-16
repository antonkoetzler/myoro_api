<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// TODO: Research and refactor this.
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// TODO: You are here.
// Gotta find out how to get these api routes working.
// Gotta find out how to standardize errors and not return that stinky HTML.
Route::post('/signup', [AuthController::class, 'signup']);
