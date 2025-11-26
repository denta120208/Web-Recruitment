<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (! Schema::hasColumn('apply_jobs', 'apply_jobs_interview_email_sent')) {
                $table->boolean('apply_jobs_interview_email_sent')
                    ->default(false)
                    ->after('apply_jobs_interview_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_interview_email_sent')) {
                $table->dropColumn('apply_jobs_interview_email_sent');
            }
        });
    }
};
