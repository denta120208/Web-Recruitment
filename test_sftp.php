<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

try {
    Log::info('Testing SFTP connection to career disk');
    
    // Test connection
    $disk = Storage::disk('career');
    $files = $disk->allFiles('/');
    
    Log::info('SFTP connection successful', [
        'files_count' => count($files),
        'files' => array_slice($files, 0, 10)
    ]);
    
    // Test write
    $testContent = 'Test file at ' . date('Y-m-d H:i:s');
    $disk->put('test_connection.txt', $testContent);
    
    Log::info('SFTP write test successful');
    
    // Test read
    $readContent = $disk->get('test_connection.txt');
    Log::info('SFTP read test successful', [
        'content' => $readContent
    ]);
    
    // Clean up
    $disk->delete('test_connection.txt');
    
    echo "SFTP test successful. Check logs for details.\n";
    
} catch (Exception $e) {
    Log::error('SFTP test failed', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    echo "SFTP test failed. Check logs for details.\n";
    echo "Error: " . $e->getMessage() . "\n";
}
