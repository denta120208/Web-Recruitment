<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobVacancyApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Job Vacancy API Routes
Route::prefix('job-vacancy')->group(function () {
    Route::post('/create', [JobVacancyApiController::class, 'store']);
    Route::post('/update/{id}', [JobVacancyApiController::class, 'update']);
    Route::get('/list', [JobVacancyApiController::class, 'index']);
    Route::get('/detail/{id}', [JobVacancyApiController::class, 'show']);
});
