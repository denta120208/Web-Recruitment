<?php

require_once 'vendor/autoload.php';

use App\Models\JobVacancy;
use App\Models\Location;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Fixing Job Vacancy Locations...\n\n";

// Mapping job names ke HRIS Location ID berdasarkan screenshot
$jobLocationMapping = [
    'tesss' => 30,  // Roku Ramen
    'tes admin' => 2,  // Metland Cileungsi (dari screenshot sebelumnya)
    'IT Software Developer Batch 4' => 1,  // Kantor Pusat
    'IT Infra Security' => 1,  // Kantor Pusat
];

echo "📋 Checking and updating job locations...\n\n";

foreach ($jobLocationMapping as $jobName => $hrisLocationId) {
    $job = JobVacancy::where('job_vacancy_name', $jobName)->first();
    
    if (!$job) {
        echo "❌ Job not found: {$jobName}\n\n";
        continue;
    }
    
    $location = Location::where('hris_location_id', $hrisLocationId)->first();
    
    if (!$location) {
        echo "❌ Location not found for HRIS ID: {$hrisLocationId}\n\n";
        continue;
    }
    
    // Update job vacancy location
    $oldLocationId = $job->job_vacancy_hris_location_id;
    $job->job_vacancy_hris_location_id = $hrisLocationId;
    $job->save();
    
    echo "✅ Updated: {$jobName}\n";
    echo "   Old HRIS Location ID: " . ($oldLocationId ?? 'NULL') . "\n";
    echo "   New HRIS Location ID: {$hrisLocationId}\n";
    echo "   Location Name: {$location->name}\n\n";
}

echo "\n🎉 Job locations updated successfully!\n";
echo "\n💡 Next steps:\n";
echo "1. Clear cache: php artisan cache:clear\n";
echo "2. Refresh the job vacancy page\n";
echo "3. Location labels should now appear\n";
