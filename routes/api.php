<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ExpenseNoteController;

use App\Http\Controllers\AuthController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

// Définition du rate limiting (Maximum 5 tentatives par minute)
RateLimiter::for('login', function () {
    return Limit::perMinute(5); 
});

//systeme d'authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {

    //Route pour users authentifié
    Route::get('/expense-notes', [ExpenseNoteController::class, 'index']);
    Route::get('/expense-notes/{id}', [ExpenseNoteController::class, 'show']);

    // Routes limitées à user_id = 1
    Route::post('/expense-notes', [ExpenseNoteController::class, 'store']);
    Route::put('/expense-notes/{id}', [ExpenseNoteController::class, 'update']);
    Route::delete('/expense-notes/{id}', [ExpenseNoteController::class, 'destroy']);
});
