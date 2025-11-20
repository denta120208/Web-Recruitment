<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Location;
use App\Models\JobVacancy;
use App\Models\ApplyJob;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Debug Admin Location Filtering...\n\n";

// Test admin cileungsi
$admin = User::where('email', 'admin.cileungsi@metland.com')->with('location')->first();

if (!$admin) {
    echo "❌ Admin not found!\n";
    exit;
}

echo "👤 Admin Info:\n";
echo "- Email: {$admin->email}\n";
echo "- Role: {$admin->role}\n";
echo "- Location ID: {$admin->location_id}\n";

if ($admin->location) {
    echo "- Location Name: {$admin->location->name}\n";
    echo "- HRIS Location ID: {$admin->location->hris_location_id}\n";
    
    // Cek job vacancy di lokasi ini
    $jobCount = JobVacancy::where('job_vacancy_hris_location_id', $admin->location->hris_location_id)->count();
    echo "- Job Vacancies di lokasi ini: {$jobCount}\n";
    
    if ($jobCount > 0) {
        $jobs = JobVacancy::where('job_vacancy_hris_location_id', $admin->location->hris_location_id)->get();
        echo "\n📋 Job Vacancies:\n";
        foreach ($jobs as $job) {
            echo "  - ID: {$job->job_vacancy_id}, Name: {$job->job_vacancy_name}\n";
        }
        
        // Cek apply jobs untuk job vacancy ini
        $applyJobCount = ApplyJob::whereHas('jobVacancy', function ($q) use ($admin) {
            $q->where('job_vacancy_hris_location_id', $admin->location->hris_location_id);
        })->count();
        
        echo "\n📝 Apply Jobs di lokasi ini: {$applyJobCount}\n";
        
        if ($applyJobCount > 0) {
            $applyJobs = ApplyJob::with('jobVacancy', 'user')
                ->whereHas('jobVacancy', function ($q) use ($admin) {
                    $q->where('job_vacancy_hris_location_id', $admin->location->hris_location_id);
                })
                ->take(5)
                ->get();
                
            echo "\n📝 Sample Apply Jobs:\n";
            foreach ($applyJobs as $apply) {
                echo "  - Apply ID: {$apply->apply_jobs_id}, Job: {$apply->jobVacancy->job_vacancy_name}, User: {$apply->user->name}\n";
            }
        }
    }
} else {
    echo "❌ Admin tidak memiliki location!\n";
}

echo "\n🔍 Total Apply Jobs (tanpa filter): " . ApplyJob::count() . "\n";
echo "🔍 Total Job Vacancies: " . JobVacancy::count() . "\n";
echo "🔍 Total Locations: " . Location::count() . "\n";

echo "\n📊 Location Summary:\n";
$locations = Location::all();
foreach ($locations as $location) {
    $jobCount = JobVacancy::where('job_vacancy_hris_location_id', $location->hris_location_id)->count();
    echo "- {$location->name} (HRIS ID: {$location->hris_location_id}): {$jobCount} jobs\n";
}
