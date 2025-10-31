<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HrisApiService
{
    protected $baseUrl;
    protected $timeout = 30;
    protected $apiKey;

    public function __construct()
    {
        // Base URL HRIS API
        $this->baseUrl = env('HRIS_API_URL', 'https://trialhris.metropolitanland.com/recruitment/api');
        $this->apiKey = env('HRIS_API_KEY', null);
    }

    /**
     * Get headers for HRIS API request
     */
    protected function getHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Add API key if configured
        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            // Or use: $headers['X-API-Key'] = $this->apiKey;
        }

        return $headers;
    }

    /**
     * Set New Candidate ke HRIS
     */
    public function setNewCandidate($data)
    {
        try {
            $payload = [
                'job_vacancy_id' => $data['job_vacancy_id'],
                'recruitment_candidate_id' => $data['recruitment_candidate_id'],
                'candidate_name' => $data['candidate_name'],
                'candidate_email' => $data['candidate_email'],
                'candidate_contact_number' => $data['candidate_contact_number'],
                'candidate_apply_date' => $data['candidate_apply_date'],
                'apply_jobs_status_id' => $data['apply_jobs_status_id'],
                'set_new_candidate_by' => $data['set_new_candidate_by'],
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/setNewCandidate', $payload);

            Log::info('HRIS API - Set New Candidate', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Set New Candidate: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set Candidate (Update Status - Except Hired)
     */
    public function setCandidate($data)
    {
        try {
            $payload = [
                'recruitment_candidate_id' => $data['recruitment_candidate_id'],
                'candidate_name' => $data['candidate_name'],
                'candidate_email' => $data['candidate_email'],
                'candidate_contact_number' => $data['candidate_contact_number'],
                'candidate_apply_date' => $data['candidate_apply_date'],
                'apply_jobs_status_id' => $data['apply_jobs_status_id'],
                'set_candidate_by' => $data['set_candidate_by'],
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/setCandidate', $payload);

            Log::info('HRIS API - Set Candidate', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Set Candidate: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set Candidate Hired (dengan data lengkap employee)
     */
    public function setCandidateHired($data)
    {
        try {   
            // Prepare payload - ensure null values are actually null, not empty strings
            $payload = [
                // Required fields dari recruitment
                'recruitment_candidate_id' => $data['recruitment_candidate_id'],
                'candidate_name' => $data['candidate_name'],
                'candidate_email' => $data['candidate_email'],
                'candidate_contact_number' => $data['candidate_contact_number'],
                'candidate_apply_date' => $data['candidate_apply_date'],
                'apply_jobs_status_id' => 5, // Hired
                'set_candidate_by' => $data['set_candidate_by'],
                
                // Data Employee - Required fields
                'joined_date' => $data['joined_date'],
                'emp_firstname' => $data['emp_firstname'],
                'emp_gender' => $data['emp_gender'],
                'emp_marital_status' => $data['emp_marital_status'],
                
                // Optional fields - convert empty strings to null
                'emp_middle_name' => !empty($data['emp_middle_name']) ? $data['emp_middle_name'] : null,
                'emp_lastname' => !empty($data['emp_lastname']) ? $data['emp_lastname'] : null,
                'emp_ktp' => !empty($data['emp_ktp']) ? $data['emp_ktp'] : null,
                'emp_dri_lice_num' => !empty($data['emp_dri_lice_num']) ? $data['emp_dri_lice_num'] : null,
                'emp_dri_lice_exp_date' => !empty($data['emp_dri_lice_exp_date']) ? $data['emp_dri_lice_exp_date'] : null,
                'emp_birthday' => !empty($data['emp_birthday']) ? $data['emp_birthday'] : null,
                'bpjs_ks' => !empty($data['bpjs_ks']) ? $data['bpjs_ks'] : null,
                'bpjs_tk' => !empty($data['bpjs_tk']) ? $data['bpjs_tk'] : null,
                'npwp' => !empty($data['npwp']) ? $data['npwp'] : null,
                // work_station removed - not supported by HRIS API
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/setCandidate', $payload);

            Log::info('HRIS API - Set Candidate Hired', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Set Candidate Hired: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reject Job Vacancy
     */
    public function rejectJobVacancy($jobVacancyId, $rejectedBy)
    {
        try {
            $payload = [
                'job_vacancy_id' => $jobVacancyId,
                'reject_by' => $rejectedBy,
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/rejectJobVacancy', $payload);

            Log::info('HRIS API - Reject Job Vacancy', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Reject Job Vacancy: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reject Candidate
     */
    public function rejectCandidate($recruitmentCandidateId, $rejectedBy)
    {
        try {
            $payload = [
                'recruitment_candidate_id' => $recruitmentCandidateId,
                'reject_candidate_by' => $rejectedBy,
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/rejectCandidate', $payload);

            Log::info('HRIS API - Reject Candidate', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Reject Candidate: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
