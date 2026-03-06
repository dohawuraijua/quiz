<?php

use App\Http\Controllers\Quiz\IsianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post("quiz/isian", [IsianController::class, 'index']);
Route::post("quiz/isian/generate", [IsianController::class, 'generateSoalTanggal']);
