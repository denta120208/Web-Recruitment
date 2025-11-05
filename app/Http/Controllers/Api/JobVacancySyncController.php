<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Services\HrisApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class JobVacancySyncController extends Controller
{
    /**
     * Check synchronization status between HRIS and Recruitment
     * Compare HRIS job vacancies with local database
     */
    public function checkSync()
    {
        try {
            $hrisService = new HrisApiService();
            
            // Try to fetch all job vacancies from HRIS
            // Note: Adjust endpoint based on actual HRIS API
            $response = Http::withHeaders($hrisService->getHeaders())
                ->timeout(30)
                ->get($hrisService->getBaseUrl() . '/job-vacancies');
            
            $hrisJobs = [];
            if ($response->successful()) {
                $data = $response->json();
                // Handle different response formats
                $hrisJobs = $data['data'] ?? $data ?? [];
            }
            
            // Get all job vacancies from local database
            $localJobs = JobVacancy::all();
            
            // Compare
            $comparison = [
                'hris_total' => count($hrisJobs),
                'local_total' => $localJobs->count(),
                'hris_jobs' => [],
                'local_jobs' => [],
                'missing_in_local' => [],
                'missing_in_hris' => [],
                'status_mapping' => []
            ];
            
            // Check which HRIS jobs are missing in local
            foreach ($hrisJobs as $hrisJob) {
                $hrisId = $hrisJob['job_request_hris_id'] ?? $hrisJob['id'] ?? null;
                $jobName = $hrisJob['job_vacancy_name'] ?? $hrisJob['job_title'] ?? 'Unknown';
                $status = $hrisJob['job_vacancy_status_id'] ?? $hrisJob['status_id'] ?? null;
                
                $comparison['hris_jobs'][] = [
                    'hris_id' => $hrisId,
                    'name' => $jobName,
                    'status' => $status,
                    'location_id' => $hrisJob['job_vacancy_hris_location_id'] ?? $hrisJob['location_id'] ?? null,
                ];
                
                // Check if exists in local
                $localJob = JobVacancy::where('job_request_hris_id', $hrisId)->first();
                if (!$localJob) {
                    $comparison['missing_in_local'][] = [
                        'hris_id' => $hrisId,
                        'name' => $jobName,
                        'status' => $status,
                        'reason' => 'Not found in local database'
                    ];
                }
            }
            
            // Check which local jobs are missing in HRIS (or check their status)
            foreach ($localJobs as $localJob) {
                $comparison['local_jobs'][] = [
                    'id' => $localJob->job_vacancy_id,
                    'hris_id' => $localJob->job_request_hris_id,
                    'name' => $localJob->job_vacancy_name,
                    'status' => $localJob->job_vacancy_status_id,
                    'is_active' => $localJob->isActive(),
                    'available_positions' => $localJob->available_positions,
                ];
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Sync status retrieved',
                'data' => $comparison
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error checking sync status: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to check sync status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get job vacancies that should be displayed on public page
     */
    public function getPublicJobs()
    {
        try {
            $jobVacancies = JobVacancy::active()
                ->withCount([
                    'applyJobs as hired_count' => function($query) {
                        $query->where('apply_jobs_status', 5);
                    }
                ])
                ->orderBy('job_vacancy_start_date', 'desc')
                ->get()
                ->map(function($job) {
                    $hiredCount = $job->hired_count ?? 0;
                    $manPower = $job->job_vacancy_man_power ?? 0;
                    $available = max(0, $manPower - $hiredCount);
                    
                    $hrisService = new HrisApiService();
                    $locationName = null;
                    if (!empty($job->job_vacancy_hris_location_id)) {
                        $locationName = $hrisService->getLocationName($job->job_vacancy_hris_location_id);
                    }
                    
                    return [
                        'id' => $job->job_vacancy_id,
                        'hris_id' => $job->job_request_hris_id,
                        'name' => $job->job_vacancy_name,
                        'level' => $job->job_vacancy_level_name,
                        'status_id' => $job->job_vacancy_status_id,
                        'location_id' => $job->job_vacancy_hris_location_id,
                        'location_name' => $locationName,
                        'man_power' => $manPower,
                        'hired_count' => $hiredCount,
                        'available_positions' => $available,
                        'start_date' => $job->job_vacancy_start_date,
                        'end_date' => $job->job_vacancy_end_date,
                        'is_active' => $job->isActive(),
                        'will_be_displayed' => $available > 0 && $job->isActive(),
                    ];
                });
            
            return response()->json([
                'success' => true,
                'message' => 'Public jobs retrieved',
                'count' => $jobVacancies->count(),
                'data' => $jobVacancies
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Error getting public jobs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get public jobs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

