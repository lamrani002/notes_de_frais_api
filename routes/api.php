<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ExpenseNoteController;

Route::prefix('expense-notes')->group(function () {
    // GET tous les notes de frais
    Route::get('/', [ExpenseNoteController::class, 'index']); 
    // GET une note de frais
    Route::get('/{id}', [ExpenseNoteController::class, 'show']); 
    // POST creer une note de frais
    Route::post('/', [ExpenseNoteController::class, 'store']); 
    // UPDATE modifier une frais
    Route::put('/{id}', [ExpenseNoteController::class, 'update']); 
    // DELETE supprimer une note de frais
    Route::delete('/{id}', [ExpenseNoteController::class, 'destroy']); 
});
