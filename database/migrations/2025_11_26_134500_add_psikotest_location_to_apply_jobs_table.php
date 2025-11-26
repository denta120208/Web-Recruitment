<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_location')) {
                $table->text('apply_jobs_psikotest_location')->nullable()->after('apply_jobs_psikotest_time');
            }
        });
    }

    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_location')) {
                $table->dropColumn('apply_jobs_psikotest_location');
            }
        });
    }
};
