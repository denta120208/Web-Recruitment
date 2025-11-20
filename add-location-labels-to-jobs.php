<?php

require_once 'vendor/autoload.php';

use App\Models\JobVacancy;
use App\Models\Location;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🏷️ Adding Location Labels to Job Vacancies...\n\n";

// Get all job vacancies
$jobs = JobVacancy::all();

echo "📋 Found " . $jobs->count() . " job vacancies\n\n";

$updated = 0;
$skipped = 0;

foreach ($jobs as $job) {
    // Cek apakah job sudah punya location label
    if ($job->job_vacancy_hris_location_id) {
        // Cari location berdasarkan hris_location_id
        $location = Location::where('hris_location_id', $job->job_vacancy_hris_location_id)->first();
        
        if ($location) {
            echo "✅ Job: {$job->job_vacancy_name}\n";
            echo "   Location: {$location->name} (HRIS ID: {$location->hris_location_id})\n";
            echo "   Status: Already has location\n\n";
            $skipped++;
        } else {
            echo "⚠️  Job: {$job->job_vacancy_name}\n";
            echo "   HRIS Location ID: {$job->job_vacancy_hris_location_id}\n";
            echo "   Status: Location not found in database\n\n";
            $skipped++;
        }
    } else {
        echo "❌ Job: {$job->job_vacancy_name}\n";
        echo "   Status: No location assigned (job_vacancy_hris_location_id is NULL)\n";
        echo "   Action: Needs manual assignment\n\n";
        $skipped++;
    }
}

echo "\n📊 Summary:\n";
echo "- Total Jobs: " . $jobs->count() . "\n";
echo "- Jobs with Location: " . ($jobs->count() - $skipped) . "\n";
echo "- Jobs without Location: " . $skipped . "\n";

echo "\n⚠️  IMPORTANT:\n";
echo "Jobs without location (job_vacancy_hris_location_id = NULL) need to be assigned manually.\n";
echo "Please update them in HRIS system or directly in database.\n";

echo "\n💡 To assign location manually, run:\n";
echo "php artisan tinker\n";
echo "\$job = JobVacancy::where('job_vacancy_name', 'YOUR_JOB_NAME')->first();\n";
echo "\$job->job_vacancy_hris_location_id = HRIS_LOCATION_ID;\n";
echo "\$job->save();\n";
