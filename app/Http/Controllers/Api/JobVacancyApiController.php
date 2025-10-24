<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class JobVacancyApiController extends Controller
{
    /**
     * Display a listing of job vacancies.
     */
    public function index()
    {
        try {
            $jobVacancies = JobVacancy::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Job vacancies retrieved successfully',
                'data' => $jobVacancies
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching job vacancies: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve job vacancies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created job vacancy from HRIS.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_request_hris_id' => 'nullable|integer',
                'job_title_hris_id' => 'nullable|integer',
                'job_vacancy_level_name' => 'nullable|string|max:255',
                'job_vacancy_name' => 'required|string|max:255',
                'job_vacancy_job_desc' => 'nullable|string',
                'job_vacancy_job_spec' => 'nullable|string',
                'job_vacancy_status_id' => 'nullable|integer',
                'job_vacancy_hris_location_id' => 'nullable|integer',
                'job_vacancy_start_date' => 'nullable|date',
                'job_vacancy_end_date' => 'nullable|date',
                'job_vacancy_man_power' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jobVacancy = JobVacancy::create($request->all());

            Log::info('Job vacancy created from HRIS', [
                'job_vacancy_id' => $jobVacancy->job_vacancy_id,
                'job_vacancy_name' => $jobVacancy->job_vacancy_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job vacancy created successfully',
                'data' => $jobVacancy
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating job vacancy: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create job vacancy',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified job vacancy.
     */
    public function show($id)
    {
        try {
            $jobVacancy = JobVacancy::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Job vacancy retrieved successfully',
                'data' => $jobVacancy
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job vacancy not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified job vacancy from HRIS.
     */
    public function update(Request $request, $id)
    {
        try {
            $jobVacancy = JobVacancy::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'job_request_hris_id' => 'nullable|integer',
                'job_title_hris_id' => 'nullable|integer',
                'job_vacancy_level_name' => 'nullable|string|max:255',
                'job_vacancy_name' => 'nullable|string|max:255',
                'job_vacancy_job_desc' => 'nullable|string',
                'job_vacancy_job_spec' => 'nullable|string',
                'job_vacancy_status_id' => 'nullable|integer',
                'job_vacancy_hris_location_id' => 'nullable|integer',
                'job_vacancy_start_date' => 'nullable|date',
                'job_vacancy_end_date' => 'nullable|date',
                'job_vacancy_man_power' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jobVacancy->update($request->all());

            Log::info('Job vacancy updated from HRIS', [
                'job_vacancy_id' => $jobVacancy->job_vacancy_id,
                'job_vacancy_name' => $jobVacancy->job_vacancy_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job vacancy updated successfully',
                'data' => $jobVacancy
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating job vacancy: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job vacancy',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
