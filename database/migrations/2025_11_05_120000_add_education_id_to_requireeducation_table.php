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
        Schema::table('requireeducation', function (Blueprint $table) {
            // Menambahkan education_id untuk menyimpan level pendidikan dari HRIS
            // nullable karena data lama belum memiliki nilai ini
            if (! Schema::hasColumn('requireeducation', 'education_id')) {
                $table->unsignedInteger('education_id')->nullable()->after('requireid');
                $table->index('education_id', 'requireeducation_education_id_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requireeducation', function (Blueprint $table) {
            if (Schema::hasColumn('requireeducation', 'education_id')) {
                $table->dropIndex('requireeducation_education_id_idx');
                $table->dropColumn('education_id');
            }
        });
    }
};


