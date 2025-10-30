<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            $table->string('apply_jobs_offering_letter_file')->nullable()->after('apply_jobs_psikotest_file');
            $table->string('apply_jobs_mcu_file')->nullable()->after('apply_jobs_offering_letter_file');
            $table->boolean('is_generated_employee')->default(false)->after('apply_jobs_mcu_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('apply_jobs', function (Blueprint $table) {
            $table->dropColumn(['apply_jobs_offering_letter_file', 'apply_jobs_mcu_file', 'is_generated_employee']);
        });
    }
};

