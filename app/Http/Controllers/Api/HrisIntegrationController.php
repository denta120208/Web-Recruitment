<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplyJob;
use App\Models\Applicant;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HrisIntegrationController extends Controller
{
    /**
     * Reject Job Vacancy - Untuk reject job vacancy dari recruitment ke HRIS
     * Endpoint: POST /api/hris/reject-job-vacancy
     */
    public function rejectJobVacancy(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_vacancy_id' => 'required|integer|exists:job_vacancy,job_vacancy_id',
                'reject_by' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jobVacancy = JobVacancy::findOrFail($request->job_vacancy_id);
            
            // Update status to rejected (3 = Closed/Rejected)
            $jobVacancy->update([
                'job_vacancy_status_id' => 3,
            ]);

            Log::info('Job Vacancy Rejected', [
                'job_vacancy_id' => $request->job_vacancy_id,
                'reject_by' => $request->reject_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success Reject Job Vacancy'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error rejecting job vacancy: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject job vacancy',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set New Candidate - Untuk add candidate baru yang dipilih HRD (pertama kali)
     * Endpoint: POST /api/hris/set-new-candidate
     */
    public function setNewCandidate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_vacancy_id' => 'required|integer|exists:job_vacancy,job_vacancy_id',
                'recruitment_candidate_id' => 'required|integer',
                'candidate_name' => 'required|string|max:255',
                'candidate_email' => 'required|email|max:255',
                'candidate_contact_number' => 'required|string|max:20',
                'candidate_apply_date' => 'required|date',
                'apply_jobs_status_id' => 'required|integer',
                'set_new_candidate_by' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Cek apakah candidate sudah ada di apply_jobs
            $existingApplyJob = ApplyJob::where('requireid', $request->recruitment_candidate_id)
                ->where('job_vacancy_id', $request->job_vacancy_id)
                ->first();

            if ($existingApplyJob) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Candidate already exists for this job vacancy'
                ], 409);
            }

            // Update atau create apply job
            $applyJob = ApplyJob::updateOrCreate(
                [
                    'requireid' => $request->recruitment_candidate_id,
                    'job_vacancy_id' => $request->job_vacancy_id,
                ],
                [
                    'apply_jobs_status' => $request->apply_jobs_status_id,
                    'apply_jobs_interview_by' => $request->set_new_candidate_by,
                    'created_at' => $request->candidate_apply_date,
                ]
            );

            DB::commit();

            Log::info('New Candidate Set', [
                'recruitment_candidate_id' => $request->recruitment_candidate_id,
                'job_vacancy_id' => $request->job_vacancy_id,
                'set_by' => $request->set_new_candidate_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success Set New Candidate Job Vacancy'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting new candidate: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to set new candidate',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set Candidate (Except Hired) - Update status candidate
     * Endpoint: POST /api/hris/set-candidate
     */
    public function setCandidate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'recruitment_candidate_id' => 'required|integer',
                'candidate_name' => 'nullable|string|max:255',
                'candidate_email' => 'nullable|email|max:255',
                'candidate_contact_number' => 'nullable|string|max:20',
                'candidate_apply_date' => 'nullable|date',
                'apply_jobs_status_id' => 'required|integer',
                'set_candidate_by' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari apply job berdasarkan recruitment_candidate_id
            $applyJob = ApplyJob::where('requireid', $request->recruitment_candidate_id)->first();

            if (!$applyJob) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Candidate not found'
                ], 404);
            }

            // Update status
            $applyJob->update([
                'apply_jobs_status' => $request->apply_jobs_status_id,
                'apply_jobs_interview_by' => $request->set_candidate_by,
            ]);

            Log::info('Candidate Status Updated', [
                'recruitment_candidate_id' => $request->recruitment_candidate_id,
                'new_status' => $request->apply_jobs_status_id,
                'updated_by' => $request->set_candidate_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success Set Candidate Job Vacancy'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating candidate status: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update candidate status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set Candidate Hired - Update status ke Hired dengan data lengkap
     * Endpoint: POST /api/hris/set-candidate-hired
     */
    public function setCandidateHired(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'recruitment_candidate_id' => 'required|integer',
                'candidate_name' => 'required|string|max:255',
                'candidate_email' => 'required|email|max:255',
                'candidate_contact_number' => 'required|string|max:20',
                'candidate_apply_date' => 'required|date',
                'apply_jobs_status_id' => 'required|integer|in:5', // 5 = Hired
                'set_candidate_by' => 'required|string|max:255',
                
                // Data employee untuk HRIS
                'joined_date' => 'required|date',
                'emp_firstname' => 'required|string|max:255',
                'emp_middle_name' => 'nullable|string|max:255',
                'emp_lastname' => 'nullable|string|max:255',
                'emp_ktp' => 'required|string|max:20',
                'emp_gender' => 'required|integer|in:1,2', // 1=Male, 2=Female
                'emp_dri_lice_num' => 'nullable|string|max:50',
                'emp_dri_lice_exp_date' => 'nullable|date',
                'emp_marital_status' => 'required|string|max:50',
                'emp_birthday' => 'required|date',
                'bpjs_ks' => 'nullable|string|max:50',
                'bpjs_tk' => 'nullable|string|max:50',
                'npwp' => 'nullable|string|max:50',
                // work_station removed - not supported by HRIS API
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Cari apply job berdasarkan recruitment_candidate_id
            $applyJob = ApplyJob::where('requireid', $request->recruitment_candidate_id)->first();

            if (!$applyJob) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Candidate not found'
                ], 404);
            }

            // Update status ke Hired
            $applyJob->update([
                'apply_jobs_status' => 5, // Hired
                'apply_jobs_interview_by' => $request->set_candidate_by,
                'is_generated_employee' => true,
            ]);

            DB::commit();

            Log::info('Candidate Hired', [
                'recruitment_candidate_id' => $request->recruitment_candidate_id,
                'joined_date' => $request->joined_date,
                'emp_ktp' => $request->emp_ktp,
                'set_by' => $request->set_candidate_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success Set Candidate Job Vacancy'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting candidate as hired: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to set candidate as hired',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject Candidate - Untuk reject candidate saja (bukan job vacancy)
     * Endpoint: POST /api/hris/reject-candidate
     */
    public function rejectCandidate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'recruitment_candidate_id' => 'required|integer',
                'reject_candidate_by' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cari apply job berdasarkan recruitment_candidate_id
            $applyJob = ApplyJob::where('requireid', $request->recruitment_candidate_id)->first();

            if (!$applyJob) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Candidate not found'
                ], 404);
            }

            // Update status ke rejected (misalnya status 6 atau sesuai kebutuhan)
            // Atau bisa soft delete
            $applyJob->delete();

            Log::info('Candidate Rejected', [
                'recruitment_candidate_id' => $request->recruitment_candidate_id,
                'rejected_by' => $request->reject_candidate_by,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Success Reject Candidate'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error rejecting candidate: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject candidate',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
