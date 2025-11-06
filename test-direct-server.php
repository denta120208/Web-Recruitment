<?php

/**
 * Test API Directly on Server
 * Upload file ini ke server dan jalankan: php test-direct-server.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\HrisIntegrationController;
use Illuminate\Http\Request;

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║           TEST API DIRECTLY ON SERVER                       ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Test data
$testData = [
    'job_vacancy_id' => 2,
    'recruitment_candidate_id' => 28,
    'candidate_name' => 'Test User',
    'candidate_email' => 'test@test.com',
    'candidate_contact_number' => '08123456789',
    'candidate_apply_date' => '2025-11-06',
    'apply_jobs_status_id' => 1,
    'set_new_candidate_by' => 'Test Script'
];

echo "📤 Request Data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

try {
    // Create request
    $request = Request::create('/api/hris/set-new-candidate', 'POST', $testData);
    $request->headers->set('Content-Type', 'application/json');
    $request->headers->set('Accept', 'application/json');
    
    // Call controller
    $controller = new HrisIntegrationController();
    $response = $controller->setNewCandidate($request);
    
    // Get response data
    $responseData = $response->getData(true);
    
    echo "📥 Response:\n";
    echo "─────────────────────────────────────────────────────────────\n";
    echo "HTTP Status: " . $response->getStatusCode() . "\n\n";
    
    if (isset($responseData['data'])) {
        echo "✅ SUCCESS - Data field exists!\n\n";
        
        echo "📚 Education Data:\n";
        if (isset($responseData['data']['last_education_id'])) {
            echo "  ✅ Education ID: " . $responseData['data']['last_education_id'] . "\n";
            echo "  ✅ Institution: " . ($responseData['data']['last_institute_education'] ?? 'N/A') . "\n";
            echo "  ✅ Major: " . ($responseData['data']['last_major_education'] ?? 'N/A') . "\n";
        } else {
            echo "  ❌ No education data\n";
        }
        
        echo "\n💼 Work Experience Data:\n";
        if (isset($responseData['data']['last_company_work_experience'])) {
            echo "  ✅ Company: " . $responseData['data']['last_company_work_experience'] . "\n";
            echo "  ✅ Jabatan: " . ($responseData['data']['last_jabatan_work_experience'] ?? 'N/A') . "\n";
        } else {
            echo "  ❌ No work experience data\n";
        }
        
    } else {
        echo "❌ FAILED - No data field in response!\n";
        echo "This means code is NOT updated on server.\n";
    }
    
    echo "\n📋 Full Response:\n";
    echo "─────────────────────────────────────────────────────────────\n";
    echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                      TEST COMPLETE                           ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
