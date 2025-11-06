<?php

/**
 * Test Script untuk API setNewCandidate
 * 
 * Script ini akan test API dan menampilkan response lengkap
 * untuk memastikan data Last Education dan Last Work Experience muncul
 */

// Data test - sesuaikan dengan data real di database
$testData = [
    'job_vacancy_id' => 1,
    'recruitment_candidate_id' => 38, // ID putri yang benar
    'candidate_name' => 'putri',
    'candidate_email' => 'putri@gmail.com',
    'candidate_contact_number' => '08123456789',
    'candidate_apply_date' => '2025-11-06',
    'apply_jobs_status_id' => 1,
    'set_new_candidate_by' => 'Test User'
];

// API Endpoint
$apiUrl = 'https://trialhris.metropolitanland.com/recruitment/api/setNewCandidate';

// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

// Execute request
echo "=== Testing API setNewCandidate ===\n\n";
echo "Request URL: {$apiUrl}\n";
echo "Request Data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

curl_close($ch);

// Display response
echo "=== Response ===\n";
echo "HTTP Code: {$httpCode}\n\n";

$responseData = json_decode($response, true);

if ($responseData) {
    echo "Response Data:\n";
    echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n\n";
    
    // Check if education and work experience data exists
    if (isset($responseData['data'])) {
        $data = $responseData['data'];
        
        echo "=== Verification ===\n";
        
        // Check Last Education
        if (isset($data['last_education_id']) && $data['last_education_id'] !== null) {
            echo "✅ Last Education ID: " . $data['last_education_id'] . "\n";
            echo "✅ Last Institute: " . ($data['last_institute_education'] ?? 'N/A') . "\n";
            echo "✅ Last Major: " . ($data['last_major_education'] ?? 'N/A') . "\n";
        } else {
            echo "❌ Last Education: NOT FOUND\n";
        }
        
        echo "\n";
        
        // Check Last Work Experience
        if (isset($data['last_company_work_experience']) && $data['last_company_work_experience'] !== null) {
            echo "✅ Last Company: " . $data['last_company_work_experience'] . "\n";
            echo "✅ Last Jabatan: " . ($data['last_jabatan_work_experience'] ?? 'N/A') . "\n";
        } else {
            echo "❌ Last Work Experience: NOT FOUND\n";
        }
    }
} else {
    echo "Raw Response:\n";
    echo $response . "\n";
}

echo "\n=== Test Complete ===\n";
