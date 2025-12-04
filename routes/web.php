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
    Route::get('/show/{id}', [ApplicantController::class, 'show'])->name('show');
    Route::get('/print/{id}', [ApplicantController::class, 'printView'])->name('print');
    Route::get('/pdf/{id}', [ApplicantController::class, 'generatePDF'])->name('pdf');

    // Serve own CV/photo file for the logged-in applicant
    Route::get('/file/{type}', [ApplicantController::class, 'serveOwnFile'])
        ->where('type', 'cv|photo')
        ->name('file');
});

// Route for serving files from mlnas disk (accept full path)
Route::get('/file/{path}', [ApplicantController::class, 'servePath'])
    ->where('path', '.*')
    ->name('file.serve');

// Protected admin routes for PDF generation
Route::get('/admin/applicant/pdf/{id}', [ApplicantController::class, 'generatePDFAdmin'])
    ->name('admin.applicant.pdf');
Route::get('/admin/applicant/print/{id}', [ApplicantController::class, 'printViewAdmin'])
    ->name('admin.applicant.print');

// Admin-only streaming for applicant files by RequireID
Route::get('/admin/file/applicant/{requireId}/{type}', [ApplicantController::class, 'serveApplicantFileAdmin'])
    ->where(['requireId' => '[0-9]+', 'type' => 'cv|photo'])
    ->name('admin.file.applicant');

// Download apply job files (psikotest, mcu, offering letter)
Route::get('/admin/file/apply-job', [ApplicantController::class, 'serveApplyJobFile'])
    ->name('admin.file.apply-job');

// Admin routes for viewing applicant data
Route::prefix('admin/applicant')->name('admin.applicant.')->group(function () {
    Route::get('/show/{id}', [ApplicantController::class, 'showAdmin'])->name('show');
    Route::get('/print/{id}', [ApplicantController::class, 'printViewAdmin'])->name('print');
    Route::get('/pdf/{id}', [ApplicantController::class, 'generatePDFAdmin'])->name('pdf');
});

// Report routes akan dihandle oleh Filament secara otomatis

// Test HRIS routes
Route::prefix('test-hris')->group(function () {
    Route::get('/applicants', [TestHrisController::class, 'getApplicants']);
    Route::get('/applicant/{id}', [TestHrisController::class, 'getApplicant']);
    Route::get('/job-vacancies', [TestHrisController::class, 'getJobVacancies']);
    Route::get('/job-vacancy/{id}', [TestHrisController::class, 'getJobVacancy']);
    Route::get('/apply-jobs', [TestHrisController::class, 'getApplyJobs']);
    Route::get('/apply-job/{id}', [TestHrisController::class, 'getApplyJob']);
});

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

    // Account management (self-service)
    Route::get('/account/delete', [\App\Http\Controllers\AuthController::class, 'showDeleteAccountForm'])->name('account.delete');
    Route::post('/account/delete', [\App\Http\Controllers\AuthController::class, 'deleteAccount'])->name('account.delete.post');
    
    // New improved delete account feature
    Route::get('/applicant/delete-account', [\App\Http\Controllers\ApplicantController::class, 'showDeleteAccount'])->name('applicant.delete.account');
    Route::post('/applicant/delete-account', [\App\Http\Controllers\ApplicantController::class, 'deleteAccount'])->name('applicant.delete.account.post');
});

// Test HRIS Connection
Route::get('/test-hris', [TestHrisController::class, 'testConnection']);
Route::get('/test-hris', [\App\Http\Controllers\TestHrisController::class, 'testSendCandidate']);

// Report Export Routes (Admin only)
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/reports/export/excel', [\App\Http\Controllers\ReportExportController::class, 'exportExcel'])
        ->name('reports.export.excel');
    Route::get('/admin/reports/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportPdf'])
        ->name('reports.export.pdf');

    Route::get('/admin/reports/{job_vacancy_id}/{status_key}/export/excel', [\App\Http\Controllers\ReportExportController::class, 'exportApplicantsExcel'])
        ->name('reports.status.export.excel');
});
