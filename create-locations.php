<?php

require_once 'vendor/autoload.php';

use App\Models\Location;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🏢 Creating Locations...\n\n";

// Data locations Metland
$locations = [
    [
        'hris_location_id' => 1,
        'name' => 'Kantor Pusat',
        'country_code' => 'ID',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta',
        'address' => 'Jakarta Pusat',
        'zip_code' => '10110',
        'phone' => '021-12345678'
    ],
    [
        'hris_location_id' => 2,
        'name' => 'Metland Cileungsi',
        'country_code' => 'ID',
        'province' => 'Jawa Barat',
        'city' => 'Bogor',
        'address' => 'Cileungsi, Bogor',
        'zip_code' => '16820',
        'phone' => '021-87654321'
    ],
    [
        'hris_location_id' => 3,
        'name' => 'Metland Tanjung',
        'country_code' => 'ID',
        'province' => 'Jawa Barat',
        'city' => 'Bekasi',
        'address' => 'Tanjung, Bekasi',
        'zip_code' => '17510',
        'phone' => '021-11111111'
    ],
    [
        'hris_location_id' => 4,
        'name' => 'Metland Tambun',
        'country_code' => 'ID',
        'province' => 'Jawa Barat',
        'city' => 'Bekasi',
        'address' => 'Tambun, Bekasi',
        'zip_code' => '17510',
        'phone' => '021-22222222'
    ],
    [
        'hris_location_id' => 5,
        'name' => 'Metland Cibitung',
        'country_code' => 'ID',
        'province' => 'Jawa Barat',
        'city' => 'Bekasi',
        'address' => 'Cibitung, Bekasi',
        'zip_code' => '17520',
        'phone' => '021-33333333'
    ],
    [
        'hris_location_id' => 6,
        'name' => 'Metland Menteng',
        'country_code' => 'ID',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta',
        'address' => 'Menteng, Jakarta',
        'zip_code' => '10310',
        'phone' => '021-44444444'
    ],
    [
        'hris_location_id' => 7,
        'name' => 'Metland Puri',
        'country_code' => 'ID',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta',
        'address' => 'Puri, Jakarta',
        'zip_code' => '11610',
        'phone' => '021-55555555'
    ],
    [
        'hris_location_id' => 8,
        'name' => 'Metland Cyber Puri',
        'country_code' => 'ID',
        'province' => 'DKI Jakarta',
        'city' => 'Jakarta',
        'address' => 'Cyber Puri, Jakarta',
        'zip_code' => '11620',
        'phone' => '021-66666666'
    ],
    [
        'hris_location_id' => 9,
        'name' => 'Metland Bekasi',
        'country_code' => 'ID',
        'province' => 'Jawa Barat',
        'city' => 'Bekasi',
        'address' => 'Bekasi Timur',
        'zip_code' => '17113',
        'phone' => '021-77777777'
    ],
    [
        'hris_location_id' => 10,
        'name' => 'Metland Bogor',
        'country_code' => 'ID',
        'province' => 'Jawa Barat',
        'city' => 'Bogor',
        'address' => 'Bogor Barat',
        'zip_code' => '16115',
        'phone' => '0251-8888888'
    ]
];

foreach ($locations as $locationData) {
    $location = Location::updateOrCreate(
        ['hris_location_id' => $locationData['hris_location_id']],
        $locationData
    );
    
    echo "✅ Created/Updated: {$location->name} (HRIS ID: {$location->hris_location_id})\n";
}

echo "\n🎉 Locations created successfully!\n";
echo "\n📋 Location Summary:\n";

$allLocations = Location::all();
foreach ($allLocations as $location) {
    echo "- ID: {$location->id}, HRIS ID: {$location->hris_location_id}, Name: {$location->name}\n";
}
