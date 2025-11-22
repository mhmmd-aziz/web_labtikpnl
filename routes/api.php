<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiController;

Route::post('/gemini/ask', [GeminiController::class, 'ask']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');