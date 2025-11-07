<?php

namespace App\Observers;

use App\Models\ApplyJob;
use App\Services\HrisApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ApplyJobObserver
{
    protected $hrisService;

    public function __construct(HrisApiService $hrisService)
    {
        $this->hrisService = $hrisService;
    }

    /**
     * Handle the ApplyJob "created" event.
     * Trigger: setNewCandidate - Ketika user apply job pertama kali
     */
    public function created(ApplyJob $applyJob)
    {
        try {
            // Hanya kirim jika ada requireid (recruitment_candidate_id)
            if (!$applyJob->requireid) {
                return;
            }

            // Get applicant profile
            $applicant = $applyJob->applicant;
            if (!$applicant) {
                Log::warning('No applicant profile found for apply job', [
                    'apply_job_id' => $applyJob->apply_jobs_id
                ]);
                return;
            }

            // Get vacancy to retrieve job_request_hris_id
            $vacancy = $applyJob->jobVacancy;
            
            // Build candidate name from applicant firstname, middlename, lastname
            $candidate_name = trim(
                ($applicant->firstname ?? '') . ' ' . 
                ($applicant->middlename ?? '') . ' ' . 
                ($applicant->lastname ?? '')
            );

            // Get email (already auto-decrypted by model accessor)
            $candidate_email = $applicant->gmail ?? $applyJob->user?->email ?? '';
            
            // Get phone (already auto-decrypted by model accessor)
            $candidate_contact_number = $applicant->phone ?? '';

            // Get last education data
            $lastEducation = $applicant->educations()->orderBy('eduid', 'desc')->first();
            
            // Get last work experience
            $lastWorkExp = $applicant->workExperiences()->orderBy('workid', 'desc')->first();

            $data = [
                'job_vacancy_id' => $vacancy?->job_request_hris_id ?? 0,
                'recruitment_candidate_id' => $applyJob->requireid,
                'candidate_name' => $candidate_name ?: 'Unknown',
                'candidate_email' => $candidate_email ?: 'no-email@example.com',
                'candidate_contact_number' => $candidate_contact_number ?: '0',
                'candidate_apply_date' => $applyJob->apply_date ?? $applyJob->created_at->format('Y-m-d'),
                'apply_jobs_status_id' => $applyJob->apply_jobs_status ?? 1,
                'set_new_candidate_by' => optional(Auth::user())->name ?? 'System',
                
                // Education data
                'last_education_id' => $lastEducation?->education_id,
                'last_institute_education' => $lastEducation?->institutionname,
                'last_major_education' => $lastEducation?->major,
                'last_year_education' => $lastEducation?->year,
                'last_score_education' => $lastEducation?->score,
                'last_start_date_education' => $lastEducation?->startdate?->format('Y-m-d'),
                'last_end_date_education' => $lastEducation?->enddate?->format('Y-m-d'),
                
                // Work experience data
                'last_company_work_experience' => $lastWorkExp?->companyname,
                'last_jabatan_work_experience' => $lastWorkExp?->joblevel,
                'last_from_date_work_experience' => $lastWorkExp?->startdate?->format('Y-m-d'),
                'last_to_date_work_experience' => $lastWorkExp?->enddate?->format('Y-m-d'),
            ];

            Log::info('ApplyJobObserver - Creating new candidate', [
                'apply_job_id' => $applyJob->apply_jobs_id,
                'data' => $data
            ]);

            $result = $this->hrisService->setNewCandidate($data);

            if (!$result['success']) {
                Log::warning('Failed to sync new candidate to HRIS', [
                    'apply_job_id' => $applyJob->apply_jobs_id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            } else {
                Log::info('Successfully synced new candidate to HRIS', [
                    'apply_job_id' => $applyJob->apply_jobs_id,
                    'response' => $result['data'] ?? null
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in ApplyJobObserver created: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the ApplyJob "updated" event.
     * Trigger: setCandidate - Ketika admin update status candidate
     */
    public function updated(ApplyJob $applyJob)
    {
        try {
            // Hanya kirim jika ada requireid
            if (!$applyJob->requireid) {
                return;
            }

            // Cek apakah status berubah
            if (!$applyJob->isDirty('apply_jobs_status')) {
                return;
            }

            $newStatus = $applyJob->apply_jobs_status;

            // Jika status = 5 (Hired), gunakan setCandidateHired dengan data lengkap
            if ($newStatus == 5) {
                // Untuk hired, perlu data employee lengkap
                // Bisa diambil dari form atau database lain
                // Untuk sementara skip dulu, nanti bisa dipanggil manual via action button
                Log::info('Candidate status changed to Hired, use manual sync for complete data', [
                    'apply_job_id' => $applyJob->apply_jobs_id
                ]);
                return;
            }

            // Get applicant profile
            $applicant = $applyJob->applicant;
            if (!$applicant) {
                Log::warning('No applicant profile found for apply job', [
                    'apply_job_id' => $applyJob->apply_jobs_id
                ]);
                return;
            }

            // Build candidate name from applicant firstname, middlename, lastname
            $candidate_name = trim(
                ($applicant->firstname ?? '') . ' ' . 
                ($applicant->middlename ?? '') . ' ' . 
                ($applicant->lastname ?? '')
            );

            // Get email (already auto-decrypted by model accessor)
            $candidate_email = $applicant->gmail ?? $applyJob->user?->email ?? '';
            
            // Get phone (already auto-decrypted by model accessor)
            $candidate_contact_number = $applicant->phone ?? '';

            // Get last education data
            $lastEducation = $applicant->educations()->orderBy('eduid', 'desc')->first();
            
            // Get last work experience
            $lastWorkExp = $applicant->workExperiences()->orderBy('workid', 'desc')->first();

            // Untuk status selain Hired, gunakan setCandidate biasa
            $data = [
                'recruitment_candidate_id' => $applyJob->requireid,
                'candidate_name' => $candidate_name ?: 'Unknown',
                'candidate_email' => $candidate_email ?: 'no-email@example.com',
                'candidate_contact_number' => $candidate_contact_number ?: '0',
                'candidate_apply_date' => $applyJob->apply_date ?? $applyJob->created_at->format('Y-m-d'),
                'apply_jobs_status_id' => $newStatus,
                'set_candidate_by' => optional(Auth::user())->name ?? 'System',
                
                // Education data
                'last_education_id' => $lastEducation?->education_id,
                'last_institute_education' => $lastEducation?->institutionname,
                'last_major_education' => $lastEducation?->major,
                'last_year_education' => $lastEducation?->year,
                'last_score_education' => $lastEducation?->score,
                'last_start_date_education' => $lastEducation?->startdate?->format('Y-m-d'),
                'last_end_date_education' => $lastEducation?->enddate?->format('Y-m-d'),
                
                // Work experience data
                'last_company_work_experience' => $lastWorkExp?->companyname,
                'last_jabatan_work_experience' => $lastWorkExp?->joblevel,
                'last_from_date_work_experience' => $lastWorkExp?->startdate?->format('Y-m-d'),
                'last_to_date_work_experience' => $lastWorkExp?->enddate?->format('Y-m-d'),
            ];

            Log::info('ApplyJobObserver - Updating candidate status', [
                'apply_job_id' => $applyJob->apply_jobs_id,
                'old_status' => $applyJob->getOriginal('apply_jobs_status'),
                'new_status' => $newStatus,
                'data' => $data
            ]);

            $result = $this->hrisService->setCandidate($data);

            if (!$result['success']) {
                Log::warning('Failed to sync candidate status to HRIS', [
                    'apply_job_id' => $applyJob->apply_jobs_id,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            } else {
                Log::info('Successfully synced candidate status to HRIS', [
                    'apply_job_id' => $applyJob->apply_jobs_id,
                    'response' => $result['data'] ?? null
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in ApplyJobObserver updated: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle the ApplyJob "deleted" event.
     * Trigger: rejectCandidate - Ketika admin delete/reject candidate
     */
    public function deleted(ApplyJob $applyJob)
    {
        try {
            // Hanya kirim jika ada requireid
            if (!$applyJob->requireid) {
                Log::info('ApplyJob deleted - No requireid, skip HRIS sync', [
                    'apply_job_id' => $applyJob->apply_jobs_id
                ]);
                return;
            }

            $rejectedBy = optional(Auth::user())->name ?? 'System';
            
            Log::info('ApplyJobObserver - Deleting candidate from HRIS', [
                'apply_job_id' => $applyJob->apply_jobs_id,
                'recruitment_candidate_id' => $applyJob->requireid,
                'rejected_by' => $rejectedBy
            ]);

            $result = $this->hrisService->rejectCandidate(
                $applyJob->requireid,
                $rejectedBy
            );

            if (!$result['success']) {
                Log::warning('Failed to sync candidate rejection to HRIS', [
                    'apply_job_id' => $applyJob->apply_jobs_id,
                    'recruitment_candidate_id' => $applyJob->requireid,
                    'error' => $result['error'] ?? 'Unknown error',
                    'status' => $result['status'] ?? null
                ]);
            } else {
                Log::info('Successfully synced candidate rejection to HRIS', [
                    'apply_job_id' => $applyJob->apply_jobs_id,
                    'recruitment_candidate_id' => $applyJob->requireid,
                    'response' => $result['data'] ?? null
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error in ApplyJobObserver deleted: ' . $e->getMessage(), [
                'apply_job_id' => $applyJob->apply_jobs_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
