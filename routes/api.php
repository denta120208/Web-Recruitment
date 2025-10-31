<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JobVacancyApiController;
use App\Http\Controllers\Api\HrisIntegrationController;

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

// HRIS Integration API Routes
Route::prefix('hris')->group(function () {
    // Reject Job Vacancy
    Route::post('/reject-job-vacancy', [HrisIntegrationController::class, 'rejectJobVacancy']);
    
    // Set New Candidate (Pertama kali)
    Route::post('/set-new-candidate', [HrisIntegrationController::class, 'setNewCandidate']);
    
    // Set Candidate (Update status - Except Hired)
    Route::post('/set-candidate', [HrisIntegrationController::class, 'setCandidate']);
    
    // Set Candidate Hired (dengan data lengkap)
    Route::post('/set-candidate-hired', [HrisIntegrationController::class, 'setCandidateHired']);
    
    // Reject Candidate
    Route::post('/reject-candidate', [HrisIntegrationController::class, 'rejectCandidate']);
});
