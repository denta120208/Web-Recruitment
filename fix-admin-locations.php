<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Location;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Fixing Admin Location IDs...\n\n";

// Mapping admin email ke location name (gunakan nama yang ada di database)
$adminLocationMapping = [
    'admin.cileungsi@metland.com' => 'Cileungsi',
    'admin.tanjung@metland.com' => 'Tanjung', 
    'admin.tambun@metland.com' => 'Tambun',
    'admin.cibitung@metland.com' => 'Cibitung',
    'admin.menteng@metland.com' => 'Menteng',
    'admin.puri@metland.com' => 'Puri',
    'admin.cyberpuri@metland.com' => 'Cyber',
    'admin.bekasi@metland.com' => 'Bekasi',
    'admin.bogor@metland.com' => 'Bogor',
    'admin.jakarta@metland.com' => 'Jakarta',
];

// Jika tidak ditemukan dengan nama pendek, coba dengan ID langsung
$adminLocationIdMapping = [
    'admin.cileungsi@metland.com' => 1,
    'admin.tanjung@metland.com' => 2, 
    'admin.tambun@metland.com' => 3,
    'admin.cibitung@metland.com' => 4,
    'admin.menteng@metland.com' => 5,
    'admin.puri@metland.com' => 6,
    'admin.cyberpuri@metland.com' => 7,
    'admin.bekasi@metland.com' => 8,
    'admin.bogor@metland.com' => 9,
    'admin.jakarta@metland.com' => 10,
];

foreach ($adminLocationMapping as $email => $locationName) {
    $user = User::where('email', $email)->first();
    if (!$user) {
        echo "❌ User not found: {$email}\n";
        continue;
    }

    $location = Location::where('name', 'like', '%' . $locationName . '%')->first();
    
    // Jika tidak ditemukan dengan nama, coba dengan ID langsung
    if (!$location && isset($adminLocationIdMapping[$email])) {
        $location = Location::find($adminLocationIdMapping[$email]);
    }
    
    if (!$location) {
        echo "❌ Location not found: {$locationName} (tried ID: " . ($adminLocationIdMapping[$email] ?? 'none') . ")\n";
        continue;
    }

    $user->location_id = $location->id;
    $user->save();

    echo "✅ Updated {$email} -> Location ID: {$location->id} ({$location->name}) -> HRIS ID: {$location->hris_location_id}\n";
}

echo "\n🎉 Admin location IDs updated successfully!\n";
echo "\n📋 Admin Summary:\n";

$admins = User::whereIn('role', ['admin_location', 'admin_pusat'])->with('location')->get();
foreach ($admins as $admin) {
    $locationInfo = $admin->location ? 
        "Location ID: {$admin->location->id}, HRIS ID: {$admin->location->hris_location_id}, Name: {$admin->location->name}" : 
        "No Location (Admin Pusat)";
    
    echo "- {$admin->email} ({$admin->role}) -> {$locationInfo}\n";
}
