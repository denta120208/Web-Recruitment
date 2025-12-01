<?php
// Test manual SFTP connection without Laravel
require_once __DIR__ . '/vendor/autoload.php';

use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

try {
    echo "Testing manual SFTP connection...\n";
    
    $host = '192.168.200.87';
    $port = 2022;
    $username = 'career';
    $password = 'pZH8pNBAZXp5H4y';
    
    echo "Connecting to $host:$port as $username...\n";
    
    $sftp = new SFTP($host, $port);
    $sftp->setTimeout(30);
    
    if (!$sftp->login($username, $password)) {
        throw new Exception("Login failed. Check credentials or server settings.");
    }
    
    echo "✓ Connection successful!\n";
    
    // List files
    echo "Files in root directory:\n";
    $files = $sftp->nlist('/');
    if ($files === false) {
        echo "Could not list files\n";
    } else {
        foreach ($files as $file) {
            echo "  - $file\n";
        }
    }
    
    // Test write
    $testFile = 'test_' . time() . '.txt';
    $testContent = 'Test at ' . date('Y-m-d H:i:s');
    
    echo "Writing test file: $testFile\n";
    if (!$sftp->put($testFile, $testContent)) {
        throw new Exception("Failed to write test file");
    }
    
    echo "✓ File written successfully\n";
    
    // Test read
    echo "Reading test file...\n";
    $readContent = $sftp->get($testFile);
    if ($readContent === false) {
        throw new Exception("Failed to read test file");
    }
    
    echo "✓ File content: $readContent\n";
    
    // Clean up
    echo "Deleting test file...\n";
    if (!$sftp->delete($testFile)) {
        throw new Exception("Failed to delete test file");
    }
    
    echo "✓ Test file deleted\n";
    echo "✓ All tests passed!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
