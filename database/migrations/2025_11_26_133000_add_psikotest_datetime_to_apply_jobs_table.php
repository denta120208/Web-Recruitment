<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_date')) {
                $table->date('apply_jobs_psikotest_date')->nullable()->after('apply_jobs_psikotest_iq_num');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_time')) {
                $table->time('apply_jobs_psikotest_time')->nullable()->after('apply_jobs_psikotest_date');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_email_sent')) {
                $table->boolean('apply_jobs_psikotest_email_sent')
                    ->default(false)
                    ->after('apply_jobs_psikotest_file');
            }
        });
    }

    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_date')) {
                $table->dropColumn('apply_jobs_psikotest_date');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_time')) {
                $table->dropColumn('apply_jobs_psikotest_time');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_email_sent')) {
                $table->dropColumn('apply_jobs_psikotest_email_sent');
            }
        });
    }
};
