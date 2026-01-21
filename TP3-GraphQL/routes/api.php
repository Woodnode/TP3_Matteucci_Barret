<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckIfAdmin;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Anciennes routes du TP2:
Route::middleware('throttle:5,1')->group(function () {

    Route::post('/signup', 'App\Http\Controllers\AuthController@register');

    Route::post('/signin', 'App\Http\Controllers\AuthController@login');
});

Route::middleware('throttle:60,1', 'auth:sanctum')->group(function () {

    Route::post('/signout', 'App\Http\Controllers\AuthController@logout');
});
