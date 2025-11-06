<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    echo "Testing email configuration...\n";
    echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
    echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
    echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
    echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
    echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n\n";
    
    echo "Sending test email...\n";
    
    Mail::raw('This is a test email from Metland Recruitment system.', function($message) {
        $message->to('test@gmail.com')
                ->subject('Test Email - Metland Recruitment')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    });
    
    echo "✓ Email sent successfully!\n";
    echo "Check your inbox at test@gmail.com\n";
    
} catch (\Exception $e) {
    echo "✗ Error sending email:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Full trace:\n";
    echo $e->getTraceAsString() . "\n";
}
