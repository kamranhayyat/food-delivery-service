<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // address routes
    Route::post('/addresses', [AddressController::class, 'createAddress']);

    // store resource routes
    Route::post('/stores', [StoreController::class, 'registerStore']);
    Route::post('/stores/process', [StoreController::class, 'processStoreRequest']);

    Route::delete('/logout', [AuthController::class, 'logout']);
});

// authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
