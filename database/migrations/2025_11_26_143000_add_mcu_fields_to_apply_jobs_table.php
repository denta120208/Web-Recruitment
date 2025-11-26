<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_date')) {
                $table->date('apply_jobs_mcu_date')->nullable()->after('apply_jobs_mcu_file');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_time')) {
                $table->time('apply_jobs_mcu_time')->nullable()->after('apply_jobs_mcu_date');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_location')) {
                $table->text('apply_jobs_mcu_location')->nullable()->after('apply_jobs_mcu_time');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_email_sent')) {
                $table->boolean('apply_jobs_mcu_email_sent')->default(false)->after('apply_jobs_mcu_location');
            }
        });
    }

    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_date')) {
                $table->dropColumn('apply_jobs_mcu_date');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_time')) {
                $table->dropColumn('apply_jobs_mcu_time');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_location')) {
                $table->dropColumn('apply_jobs_mcu_location');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_mcu_email_sent')) {
                $table->dropColumn('apply_jobs_mcu_email_sent');
            }
        });
    }
};
