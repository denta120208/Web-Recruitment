<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

try {
    Log::info('Testing SFTP connection with detailed debug');
    
    // Get config values
    $config = [
        'host' => env('CAREER_HOST', '192.168.200.87'),
        'username' => env('CAREER_USERNAME', 'career'),
        'password' => env('CAREER_PASSWORD'),
        'port' => (int) env('CAREER_PORT', 2022),
        'root' => env('CAREER_ROOT', '/'),
        'timeout' => 30,
    ];
    
    Log::info('SFTP config', $config);
    
    // Test basic connection without listing
    $disk = Storage::disk('career');
    
    // Try to check if disk exists (basic connection test)
    $adapter = $disk->getAdapter();
    Log::info('SFTP adapter class', ['class' => get_class($adapter)]);
    
    // Try simple file existence check (less intensive than listing)
    $exists = $disk->exists('/');
    Log::info('SFTP root exists check', ['exists' => $exists]);
    
    // Try to write a simple test file
    $testPath = 'test_' . time() . '.txt';
    $testContent = 'Test at ' . date('Y-m-d H:i:s');
    
    Log::info('Attempting to write test file', ['path' => $testPath]);
    $disk->put($testPath, $testContent);
    Log::info('Test file written successfully');
    
    // Try to read it back
    $readContent = $disk->get($testPath);
    Log::info('Test file read successfully', ['content' => $readContent]);
    
    // Clean up
    $disk->delete($testPath);
    Log::info('Test file deleted successfully');
    
    echo "SFTP test successful! Check logs for details.\n";
    
} catch (Exception $e) {
    Log::error('SFTP test failed', [
        'error' => $e->getMessage(),
        'error_class' => get_class($e),
        'trace' => $e->getTraceAsString()
    ]);
    
    echo "SFTP test failed: " . $e->getMessage() . "\n";
    echo "Check logs for detailed trace.\n";
}
