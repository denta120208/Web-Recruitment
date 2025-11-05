<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\TestHrisController;
use Illuminate\Http\Request;

Route::get('/', [JobVacancyController::class, 'index']);

// Authentication routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
// Registration verification (OTP)
Route::get('/register/verify', [AuthController::class, 'showVerifyForm'])->name('register.verify');
Route::post('/register/verify', [AuthController::class, 'verifyRegister'])->name('register.verify.post');
// Forgot Password routes
Route::get('/password/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/password/verify', [AuthController::class, 'showVerifyPasswordForm'])->name('password.verify');
Route::post('/password/verify', [AuthController::class, 'verifyPasswordOtp'])->name('password.verify.post');
Route::get('/password/reset', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Job Vacancy routes
Route::post('/job-vacancy/apply', [JobVacancyController::class, 'apply'])->name('job-vacancy.apply')->middleware('auth');
Route::get('/job-vacancy/{id}', [JobVacancyController::class, 'show'])->name('job-vacancy.show');

// Applicant routes (protected by auth middleware)
Route::prefix('applicant')->name('applicant.')->middleware(['auth', 'check.existing.application', 'profile.freshness', 'force.profile.completion'])->group(function () {
    Route::get('/', [JobVacancyController::class, 'index'])->name('index');
    Route::get('/create', [ApplicantController::class, 'create'])->name('create');
    Route::post('/store', [ApplicantController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ApplicantController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ApplicantController::class, 'update'])->name('update');
    Route::get('/success', [ApplicantController::class, 'success'])->name('success');
});

// Route for serving files from mlnas disk (accept full path)
Route::get('/file/{path}', [ApplicantController::class, 'servePath'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('file.serve');

// Admin-only streaming for applicant files by RequireID
Route::get('/admin/file/applicant/{requireId}/{type}', [ApplicantController::class, 'serveApplicantFileAdmin'])
    ->where(['requireId' => '[0-9]+', 'type' => 'cv|photo'])
    ->name('admin.file.applicant');

// Download apply job files (psikotest, mcu, offering letter)
Route::get('/admin/file/apply-job', [ApplicantController::class, 'serveApplyJobFile'])
    ->name('admin.file.apply-job');

// Terms routes (show and accept)
Route::middleware('auth')->group(function(){
    Route::get('/terms', function(){
        return view('auth.terms');
    })->name('terms.show');

    Route::post('/terms/accept', function(Request $request){
        $user = $request->user();
        if ($user) {
            $user->accepted_terms_at = now();
            $user->save();
        }
        return redirect()->route('applicant.create');
    })->name('terms.accept');
});

// Test HRIS Connection
Route::get('/test-hris', [TestHrisController::class, 'testConnection']);
Route::get('/test-hris', [\App\Http\Controllers\TestHrisController::class, 'testSendCandidate']);
