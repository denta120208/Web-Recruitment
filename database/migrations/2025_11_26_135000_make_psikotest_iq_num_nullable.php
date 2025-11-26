<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_iq_num')) {
                $table->integer('apply_jobs_psikotest_iq_num')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            if (Schema::hasColumn('apply_jobs', 'apply_jobs_psikotest_iq_num')) {
                $table->integer('apply_jobs_psikotest_iq_num')->nullable(false)->default(0)->change();
            }
        });
    }
};
