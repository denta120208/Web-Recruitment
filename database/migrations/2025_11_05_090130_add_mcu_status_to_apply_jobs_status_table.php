<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert MCU status with ID 6
        DB::table('apply_jobs_status')->insert([
            'apply_jobs_status_id' => 6,
            'apply_jobs_status_name' => 'MCU',
            'trial316' => 'T',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete MCU status
        DB::table('apply_jobs_status')
            ->where('apply_jobs_status_id', 6)
            ->delete();
    }
};
