<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "Testing FTP connection to MLNAS...\n\n";

$disk = Storage::disk('mlnas');

// Test 1: List files in psikotest directory
echo "Test 1: Listing files in 'psikotest' directory:\n";
try {
    $files = $disk->files('psikotest');
    echo "Found " . count($files) . " files:\n";
    foreach ($files as $file) {
        echo "  - $file\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 2: Check if specific file exists
$testFile = 'psikotest/MOM_00031_HO_IT_10_25 (2).pdf';
echo "Test 2: Checking if '$testFile' exists:\n";
try {
    $exists = $disk->exists($testFile);
    echo $exists ? "YES, file exists!\n" : "NO, file not found!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: Try to read file
echo "Test 3: Trying to read file:\n";
try {
    $content = $disk->get($testFile);
    echo "SUCCESS! File size: " . strlen($content) . " bytes\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
