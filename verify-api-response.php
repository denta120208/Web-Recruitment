<?php

/**
 * Verify API Response - Cek apakah API mengirim data education dan work experience
 */

// Test dengan candidate putrisik yang sudah punya data education dan work experience
$testData = [
    'job_vacancy_id' => 3, // Ganti ke job_vacancy yang berbeda
    'recruitment_candidate_id' => 40, // ID putrisik
    'candidate_name' => 'putrisik',
    'candidate_email' => 'putrisik@test.com',
    'candidate_contact_number' => '08123456789',
    'candidate_apply_date' => '2025-11-06',
    'apply_jobs_status_id' => 1,
    'set_new_candidate_by' => 'Test User'
];

$apiUrl = 'https://trialhris.metropolitanland.com/recruitment/api/setNewCandidate';

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║          VERIFY API RESPONSE - setNewCandidate               ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

echo "📤 REQUEST:\n";
echo "URL: {$apiUrl}\n";
echo "Candidate ID: {$testData['recruitment_candidate_id']}\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "❌ cURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit(1);
}

curl_close($ch);

echo "📥 RESPONSE (HTTP {$httpCode}):\n";
echo str_repeat("─", 60) . "\n";

$responseData = json_decode($response, true);

if ($responseData && isset($responseData['data'])) {
    $data = $responseData['data'];
    
    echo "✅ Status: " . $responseData['status'] . "\n";
    echo "✅ Message: " . $responseData['message'] . "\n\n";
    
    echo "📚 LAST EDUCATION:\n";
    echo str_repeat("─", 60) . "\n";
    if ($data['last_education_id']) {
        echo "  ✅ Education ID: " . $data['last_education_id'] . "\n";
        echo "  ✅ Institution: " . ($data['last_institute_education'] ?? 'N/A') . "\n";
        echo "  ✅ Major: " . ($data['last_major_education'] ?? 'N/A') . "\n";
        echo "  ✅ Year: " . ($data['last_year_education'] ?? 'N/A') . "\n";
        echo "  ✅ Score: " . ($data['last_score_education'] ?? 'N/A') . "\n";
        echo "  ✅ Start Date: " . ($data['last_start_date_education'] ?? 'N/A') . "\n";
        echo "  ✅ End Date: " . ($data['last_end_date_education'] ?? 'N/A') . "\n";
    } else {
        echo "  ❌ NO EDUCATION DATA\n";
    }
    
    echo "\n💼 LAST WORK EXPERIENCE:\n";
    echo str_repeat("─", 60) . "\n";
    if ($data['last_company_work_experience']) {
        echo "  ✅ Company: " . $data['last_company_work_experience'] . "\n";
        echo "  ✅ Jabatan: " . ($data['last_jabatan_work_experience'] ?? 'N/A') . "\n";
        echo "  ✅ From Date: " . ($data['last_from_date_work_experience'] ?? 'N/A') . "\n";
        echo "  ✅ To Date: " . ($data['last_to_date_work_experience'] ?? 'N/A') . "\n";
    } else {
        echo "  ❌ NO WORK EXPERIENCE DATA\n";
    }
    
    echo "\n" . str_repeat("═", 60) . "\n";
    echo "📋 FULL RESPONSE DATA:\n";
    echo str_repeat("═", 60) . "\n";
    echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    
} else {
    echo "❌ NO DATA FIELD IN RESPONSE\n";
    echo "Raw Response:\n";
    echo $response . "\n";
}

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║                    VERIFICATION COMPLETE                     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
