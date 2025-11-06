<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RequireEducation;
use App\Models\RequireWorkExperience;
use Illuminate\Support\Facades\DB;

echo "=== Test Save Education & Work Experience ===\n\n";

$testCandidateId = 1101; // Kurnia Mega

// Check existing education data
echo "1. Checking existing education data for candidate $testCandidateId:\n";
$educations = RequireEducation::where('requireid', $testCandidateId)->get();
echo "   Found " . $educations->count() . " education records\n";
foreach ($educations as $edu) {
    echo "   - ID: {$edu->eduid}, Education ID: {$edu->education_id}, Institute: {$edu->institutionname}, Major: {$edu->major}\n";
}
echo "\n";

// Check existing work experience data
echo "2. Checking existing work experience data for candidate $testCandidateId:\n";
$workExps = RequireWorkExperience::where('requireid', $testCandidateId)->get();
echo "   Found " . $workExps->count() . " work experience records\n";
foreach ($workExps as $work) {
    echo "   - ID: {$work->workid}, Company: {$work->companyname}, Job Level: {$work->joblevel}\n";
}
echo "\n";

// Test insert new education
echo "3. Testing insert new education data:\n";
try {
    $newEdu = RequireEducation::create([
        'requireid' => $testCandidateId,
        'education_id' => 1,
        'institutionname' => 'TEST INSTITUTE',
        'major' => 'TEST MAJOR',
        'year' => 2021,
        'score' => 4,
        'startdate' => '2021-01-01',
        'enddate' => '2021-12-31',
    ]);
    echo "   ✓ Education created with ID: {$newEdu->eduid}\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Test insert new work experience
echo "4. Testing insert new work experience data:\n";
try {
    $newWork = RequireWorkExperience::create([
        'requireid' => $testCandidateId,
        'companyname' => 'PT. TEST COMPANY',
        'joblevel' => 'TEST POSITION',
        'startdate' => '2024-01-01',
        'enddate' => '2024-12-31',
    ]);
    echo "   ✓ Work experience created with ID: {$newWork->workid}\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Verify data was saved
echo "5. Verifying data after insert:\n";
$educations = RequireEducation::where('requireid', $testCandidateId)->get();
echo "   Education records: " . $educations->count() . "\n";
$workExps = RequireWorkExperience::where('requireid', $testCandidateId)->get();
echo "   Work experience records: " . $workExps->count() . "\n";
echo "\n";

echo "=== Test Complete ===\n";
