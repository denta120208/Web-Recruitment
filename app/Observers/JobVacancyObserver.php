<?php

namespace App\Observers;

use App\Models\JobVacancy;
use App\Services\HrisApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class JobVacancyObserver
{
    protected $hrisService;

    public function __construct(HrisApiService $hrisService)
    {
        $this->hrisService = $hrisService;
    }

    /**
     * Handle the JobVacancy "updated" event.
     * Trigger: rejectJobVacancy - Ketika admin reject job vacancy
     */
    public function updated(JobVacancy $jobVacancy)
    {
        try {
            // Cek apakah status berubah menjadi rejected (3 = Closed/Rejected)
            if (!$jobVacancy->isDirty('job_vacancy_status_id')) {
                return;
            }

            $newStatus = $jobVacancy->job_vacancy_status_id;

            // Jika status = 3 (Rejected/Closed), kirim ke HRIS
            if ($newStatus == 3) {
                $result = $this->hrisService->rejectJobVacancy(
                    $jobVacancy->job_request_hris_id ?? $jobVacancy->job_vacancy_id,
                    optional(Auth::user())->name ?? 'System'
                );

                if (!$result['success']) {
                    Log::warning('Failed to sync job vacancy rejection to HRIS', [
                        'job_vacancy_id' => $jobVacancy->job_vacancy_id,
                        'error' => $result['error'] ?? 'Unknown error'
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Error in JobVacancyObserver updated: ' . $e->getMessage());
        }
    }

    /**
     * Handle the JobVacancy "deleted" event.
     * Trigger: rejectJobVacancy - Ketika admin delete job vacancy
     */
    public function deleted(JobVacancy $jobVacancy)
    {
        try {
            // Hanya kirim jika ada job_request_hris_id (sudah sync ke HRIS)
            if (!$jobVacancy->job_request_hris_id) {
                Log::info('Job vacancy tidak memiliki job_request_hris_id, skip reject API', [
                    'job_vacancy_id' => $jobVacancy->job_vacancy_id
                ]);
                return;
            }

            $result = $this->hrisService->rejectJobVacancy(
                $jobVacancy->job_request_hris_id, // Gunakan job_request_hris_id dari HRIS
                optional(Auth::user())->name ?? 'System'
            );

            if (!$result['success']) {
                Log::warning('Failed to sync job vacancy deletion to HRIS', [
                    'job_vacancy_id' => $jobVacancy->job_vacancy_id,
                    'job_request_hris_id' => $jobVacancy->job_request_hris_id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            } else {
                Log::info('Successfully synced job vacancy deletion to HRIS', [
                    'job_vacancy_id' => $jobVacancy->job_vacancy_id,
                    'job_request_hris_id' => $jobVacancy->job_request_hris_id,
                    'response' => $result['data'] ?? null
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in JobVacancyObserver deleted: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
