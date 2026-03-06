<?php

use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Quiz\IsianController;
use App\Http\Controllers\Quiz\IsianHasilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post("dashboard/stats", [Dashboard::class, 'index']);
Route::post("quiz/isian-hasil", [IsianHasilController::class, 'index']);
Route::post("quiz/isian", [IsianController::class, 'index']);
Route::post("quiz/isian/generate", [IsianController::class, 'generateSoalTanggal']);
Route::get("quiz/isian/check", [IsianController::class, 'cekHasil']);
