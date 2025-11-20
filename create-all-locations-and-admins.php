<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🏢 Creating All Locations and Admin Accounts...\n\n";

// Data semua lokasi dari HRIS (sesuai list yang benar)
$locations = [
    ['hris_id' => 1, 'name' => 'Kantor Pusat', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.pusat'],
    ['hris_id' => 2, 'name' => 'Metland Transyogi', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.transyogi'],
    ['hris_id' => 3, 'name' => 'Metland Cileungsi', 'province' => 'Jawa Barat', 'city' => 'Bogor', 'admin_email' => 'admin.cileungsi'],
    ['hris_id' => 4, 'name' => 'Metland Tambun', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.tambun'],
    ['hris_id' => 5, 'name' => 'Metland Cibitung', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.cibitung'],
    ['hris_id' => 6, 'name' => 'Metland Menteng', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.menteng'],
    ['hris_id' => 7, 'name' => 'Metland Puri', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.puri'],
    ['hris_id' => 8, 'name' => 'Metland Cyber Puri', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.cyberpuri'],
    ['hris_id' => 9, 'name' => 'Mal Metropolitan Bekasi', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.malbekasi'],
    ['hris_id' => 10, 'name' => 'M Gold Tower', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.goldtower'],
    ['hris_id' => 11, 'name' => 'Grand Metropolitan Mall', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.grandmetmall'],
    ['hris_id' => 12, 'name' => 'Mal Metropolitan Cileungsi', 'province' => 'Jawa Barat', 'city' => 'Bogor', 'admin_email' => 'admin.malcileungsi'],
    ['hris_id' => 13, 'name' => 'Kaliana Apartment', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.kaliana'],
    ['hris_id' => 14, 'name' => 'Metland Hotel Cirebon', 'province' => 'Jawa Barat', 'city' => 'Cirebon', 'admin_email' => 'admin.hotelcirebon'],
    ['hris_id' => 15, 'name' => 'Hotel Horison Ultima Bekasi', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.horisonbekasi'],
    ['hris_id' => 16, 'name' => 'Hotel Horison Ultima Seminyak', 'province' => 'Bali', 'city' => 'Bali', 'admin_email' => 'admin.horisonseminyak'],
    ['hris_id' => 17, 'name' => 'Plaza Metropolitan', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.plazamet'],
    ['hris_id' => 18, 'name' => 'Metland Hotel Bekasi', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.hotelbekasi'],
    ['hris_id' => 19, 'name' => 'Kantor Pusat - MT Haryono', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.mtharyono'],
    ['hris_id' => 20, 'name' => 'Kantor Pusat - Hotel Division', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.hoteldiv'],
    ['hris_id' => 21, 'name' => 'Metland Smara Kertajati', 'province' => 'Jawa Barat', 'city' => 'Majalengka', 'admin_email' => 'admin.smarakertajati'],
    ['hris_id' => 22, 'name' => 'Metland Cikarang', 'province' => 'Jawa Barat', 'city' => 'Cikarang', 'admin_email' => 'admin.cikarang'],
    ['hris_id' => 23, 'name' => 'One District Puri', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.onedistrict'],
    ['hris_id' => 24, 'name' => 'Metland Venya Ubud', 'province' => 'Bali', 'city' => 'Bali', 'admin_email' => 'admin.venyaubud'],
    ['hris_id' => 25, 'name' => 'Recreation & Sport Facility', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.recreation'],
    ['hris_id' => 26, 'name' => 'Koperasi Metland Maju Bersama', 'province' => 'Jawa Barat', 'city' => 'Bekasi', 'admin_email' => 'admin.koperasi'],
    ['hris_id' => 27, 'name' => 'Metland Kertajati', 'province' => 'Jawa Barat', 'city' => 'Majalengka', 'admin_email' => 'admin.kertajati'],
    ['hris_id' => 28, 'name' => 'DIUBUD', 'province' => 'Bali', 'city' => 'Bali', 'admin_email' => 'admin.diubud'],
    ['hris_id' => 29, 'name' => 'Roku Ramen', 'province' => 'DKI Jakarta', 'city' => 'Jakarta', 'admin_email' => 'admin.rokuramen'],
];

echo "📍 Creating Locations...\n";
$createdLocations = [];

foreach ($locations as $loc) {
    $location = Location::updateOrCreate(
        ['hris_location_id' => $loc['hris_id']],
        [
            'name' => $loc['name'],
            'country_code' => 'ID',
            'province' => $loc['province'],
            'city' => $loc['city'],
            'address' => $loc['city'],
            'zip_code' => '00000',
            'phone' => '021-00000000'
        ]
    );
    
    $createdLocations[$loc['hris_id']] = $location;
    echo "✅ Location: {$location->name} (HRIS ID: {$location->hris_location_id}, DB ID: {$location->id})\n";
}

echo "\n👥 Creating Admin Accounts...\n";

// Buat admin pusat
$adminPusat = User::updateOrCreate(
    ['email' => 'admin.pusat@metland.com'],
    [
        'name' => 'Admin Pusat',
        'password' => Hash::make('admin123'),
        'role' => 'admin_pusat',
        'location_id' => null,
        'email_verified_at' => now(),
    ]
);
echo "✅ Admin Pusat: admin.pusat@metland.com\n";

// Buat admin untuk setiap lokasi
foreach ($locations as $loc) {
    if ($loc['hris_id'] == 1) continue; // Skip kantor pusat
    
    $location = $createdLocations[$loc['hris_id']];
    $email = $loc['admin_email'] . '@metland.com';
    
    $admin = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => 'Admin ' . $loc['name'],
            'password' => Hash::make('admin123'),
            'role' => 'admin_location',
            'location_id' => $location->id,
            'email_verified_at' => now(),
        ]
    );
    
    echo "✅ Admin: {$email} -> {$loc['name']}\n";
}

echo "\n🎉 All locations and admin accounts created successfully!\n";
echo "\n📋 Total Summary:\n";
echo "- Total Locations: " . count($locations) . "\n";
echo "- Total Admin Accounts: " . count($locations) . " (1 pusat + " . (count($locations) - 1) . " lokasi)\n";
echo "- Default Password: admin123\n";
echo "\n📄 Check ADMIN_ACCOUNTS_COMPLETE.md for full documentation\n";
