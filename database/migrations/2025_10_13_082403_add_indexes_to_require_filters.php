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
        Schema::table('Require', function (Blueprint $table) {
            // Indexes to speed up admin filters/search
            if (! Schema::hasColumn('Require', 'status')) {
                return; // safety
            }
            $table->index('status', 'require_status_idx');
            $table->index('Gender', 'require_gender_idx');
            $table->index('City', 'require_city_idx');
            // Composite index for common search fields
            $table->index(['FirstName', 'MiddleName', 'LastName'], 'require_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Require', function (Blueprint $table) {
            $table->dropIndex('require_status_idx');
            $table->dropIndex('require_gender_idx');
            $table->dropIndex('require_city_idx');
            $table->dropIndex('require_name_idx');
        });
    }
};
