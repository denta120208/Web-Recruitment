<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Location;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Assigning Admin Locations Manually...\n\n";

// Tampilkan semua locations yang ada
echo "📍 Available Locations:\n";
$locations = Location::all();
foreach ($locations as $location) {
    echo "- ID: {$location->id}, Name: {$location->name}, HRIS ID: {$location->hris_location_id}\n";
}

echo "\n👥 Assigning locations to admins:\n";

// Assign location berdasarkan database ID locations (PostgreSQL)
// Sesuai dengan screenshot database
$assignments = [
    'admin.cileungsi@metland.com' => 2,   // Metland Cileungsi (DB ID: 2, HRIS ID: 2)
    'admin.tanjung@metland.com' => 3,     // Metland Tanjung (DB ID: 3, HRIS ID: 3)
    'admin.tambun@metland.com' => 4,      // Metland Tambun (DB ID: 4, HRIS ID: 4)
    'admin.cibitung@metland.com' => 5,    // Metland Cibitung (DB ID: 5, HRIS ID: 5)
    'admin.menteng@metland.com' => 6,     // Metland Menteng (DB ID: 6, HRIS ID: 6)
    'admin.puri@metland.com' => 7,        // Metland Puri (DB ID: 7, HRIS ID: 7)
    'admin.cyberpuri@metland.com' => 8,   // Metland Cyber Puri (DB ID: 8, HRIS ID: 8)
    'admin.bekasi@metland.com' => 9,      // Metland Bekasi (DB ID: 9, HRIS ID: 9)
    'admin.bogor@metland.com' => 10,      // Metland Bogor (DB ID: 10, HRIS ID: 10)
    'admin.jakarta@metland.com' => 1,     // Kantor Pusat (DB ID: 1, HRIS ID: 1)
];

foreach ($assignments as $email => $locationId) {
    $user = User::where('email', $email)->first();
    $location = Location::find($locationId);
    
    if (!$user) {
        echo "❌ User not found: {$email}\n";
        continue;
    }
    
    if (!$location) {
        echo "❌ Location ID {$locationId} not found for {$email}\n";
        continue;
    }
    
    $user->location_id = $location->id;
    $user->save();
    
    echo "✅ {$email} -> Location: {$location->name} (ID: {$location->id}, HRIS: {$location->hris_location_id})\n";
}

echo "\n🎉 Done! Testing admin cileungsi:\n";

// Test admin cileungsi
$admin = User::where('email', 'admin.cileungsi@metland.com')->with('location')->first();
if ($admin && $admin->location) {
    echo "✅ Admin Cileungsi now has location: {$admin->location->name} (HRIS ID: {$admin->location->hris_location_id})\n";
    
    // Test job vacancy count
    $jobCount = \App\Models\JobVacancy::where('job_vacancy_hris_location_id', $admin->location->hris_location_id)->count();
    echo "📋 Job vacancies in this location: {$jobCount}\n";
} else {
    echo "❌ Admin Cileungsi still has no location\n";
}
