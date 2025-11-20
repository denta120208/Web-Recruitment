<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Admin Pusat
        $this->createAdmin('Admin Pusat', 'admin.pusat@metland.com', 'admin_pusat', null);

        // Admin Lokasi Manual
        $adminData = [
            ['name' => 'Admin Metland Cileungsi', 'email' => 'admin.cileungsi@metland.com', 'location_name' => 'Metland Cileungsi'],
            ['name' => 'Admin Metland Tanjung', 'email' => 'admin.tanjung@metland.com', 'location_name' => 'Metland Tanjung'],
            ['name' => 'Admin Metland Tambun', 'email' => 'admin.tambun@metland.com', 'location_name' => 'Metland Tambun'],
            ['name' => 'Admin Metland Cibitung', 'email' => 'admin.cibitung@metland.com', 'location_name' => 'Metland Cibitung'],
            ['name' => 'Admin Metland Menteng', 'email' => 'admin.menteng@metland.com', 'location_name' => 'Metland Menteng'],
            ['name' => 'Admin Metland Puri', 'email' => 'admin.puri@metland.com', 'location_name' => 'Metland Puri'],
            ['name' => 'Admin Metland Cyber Puri', 'email' => 'admin.cyberpuri@metland.com', 'location_name' => 'Metland Cyber Puri'],
            ['name' => 'Admin Metland Bekasi', 'email' => 'admin.bekasi@metland.com', 'location_name' => 'Metland Bekasi'],
            ['name' => 'Admin Metland Bogor', 'email' => 'admin.bogor@metland.com', 'location_name' => 'Bogor Barat'],
            ['name' => 'Admin Metland Jakarta', 'email' => 'admin.jakarta@metland.com', 'location_name' => 'DKI Jakarta'],
        ];

        foreach ($adminData as $admin) {
            $location = Location::where('name', 'like', '%' . $admin['location_name'] . '%')->first();
            $locationId = $location ? $location->id : null;
            
            $this->createAdmin($admin['name'], $admin['email'], 'admin_location', $locationId);
        }

        $this->command->info('✅ Admin users created successfully!');
        $this->command->info('📋 Check ADMIN_ACCOUNTS.md for login credentials');
    }

    private function createAdmin($name, $email, $role, $locationId)
    {
        try {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('admin123'),
                    'role' => $role,
                    'location_id' => $locationId,
                    'email_verified_at' => now(),
                ]
            );
            $this->command->info("✅ Created: {$email}");
        } catch (\Exception $e) {
            $this->command->error("❌ Failed to create: {$email} - " . $e->getMessage());
        }
    }
}
