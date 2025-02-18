<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VaultItemController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/tokenize', [VaultItemController::class, 'tokenize'])->middleware(['client:api-tokenize'])->name('tokenize');

Route::post('/detokenize', [VaultItemController::class, 'detokenize'])->middleware(['client:api-detokenize'])->name('detokenize');