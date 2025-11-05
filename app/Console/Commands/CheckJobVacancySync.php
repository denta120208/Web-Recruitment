<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobVacancy;
use App\Services\HrisApiService;
use Illuminate\Support\Facades\Log;

class CheckJobVacancySync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobvacancy:check-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check synchronization status between HRIS and Recruitment system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking Job Vacancy Synchronization...');
        $this->newLine();
        
        // Get all job vacancies from local database
        $localJobs = JobVacancy::withCount([
            'applyJobs as hired_count' => function($query) {
                $query->where('apply_jobs_status', 5);
            }
        ])->get();
        
        $this->info("Total Job Vacancies in Database: {$localJobs->count()}");
        $this->newLine();
        
        // Display all jobs with details
        $headers = ['ID', 'HRIS ID', 'Name', 'Status', 'Location ID', 'Man Power', 'Hired', 'Available', 'Active?', 'Public?'];
        $rows = [];
        
        foreach ($localJobs as $job) {
            $hiredCount = $job->hired_count ?? 0;
            $manPower = $job->job_vacancy_man_power ?? 0;
            $available = max(0, $manPower - $hiredCount);
            $isActive = $job->isActive() ? 'Yes' : 'No';
            $willDisplay = ($available > 0 && $job->isActive()) ? 'Yes' : 'No';
            
            $rows[] = [
                $job->job_vacancy_id,
                $job->job_request_hris_id ?? 'N/A',
                $job->job_vacancy_name,
                $job->job_vacancy_status_id,
                $job->job_vacancy_hris_location_id ?? 'N/A',
                $manPower,
                $hiredCount,
                $available,
                $isActive,
                $willDisplay,
            ];
        }
        
        $this->table($headers, $rows);
        $this->newLine();
        
        // Summary
        $activeCount = $localJobs->filter(fn($job) => $job->isActive())->count();
        $publicCount = $localJobs->filter(function($job) {
            $hiredCount = $job->hired_count ?? 0;
            $manPower = $job->job_vacancy_man_power ?? 0;
            $available = max(0, $manPower - $hiredCount);
            return ($available > 0 && $job->isActive());
        })->count();
        
        $this->info("Summary:");
        $this->line("  - Active Jobs: {$activeCount}");
        $this->line("  - Jobs Displayed on Public Page: {$publicCount}");
        $this->newLine();
        
        // Check location mapping
        $this->info('Checking Location Mapping...');
        $hrisService = new HrisApiService();
        $jobsWithLocation = $localJobs->filter(fn($job) => !empty($job->job_vacancy_hris_location_id));
        
        foreach ($jobsWithLocation as $job) {
            $locationName = $hrisService->getLocationName($job->job_vacancy_hris_location_id);
            $status = $locationName ? '✓' : '✗';
            $this->line("  {$status} Location ID {$job->job_vacancy_hris_location_id}: " . ($locationName ?? 'Not found'));
        }
        
        $this->newLine();
        $this->info('Use API endpoint: GET /api/sync/public-jobs to see jobs that should be displayed');
        $this->info('Use API endpoint: GET /api/sync/check to compare with HRIS');
        
        return 0;
    }
}
