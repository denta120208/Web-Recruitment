<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_interview_location')) {
                $table->string('apply_jobs_interview_location')->nullable()->after('apply_jobs_interview_by');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_interview_date')) {
                $table->date('apply_jobs_interview_date')->nullable()->after('apply_jobs_interview_location');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_interview_time')) {
                $table->time('apply_jobs_interview_time')->nullable()->after('apply_jobs_interview_date');
            }
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_interview_user_email')) {
                $table->string('apply_jobs_interview_user_email')->nullable()->after('apply_jobs_interview_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_interview_time')) {
                $table->dropColumn('apply_jobs_interview_time');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_interview_date')) {
                $table->dropColumn('apply_jobs_interview_date');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_interview_location')) {
                $table->dropColumn('apply_jobs_interview_location');
            }
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_interview_user_email')) {
                $table->dropColumn('apply_jobs_interview_user_email');
            }
        });
    }
};




