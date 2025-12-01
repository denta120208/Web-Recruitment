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
        // Add is_delete to users table
        if (! Schema::hasColumn('users', 'is_delete')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_delete')->default(false)->index()->after('role');
            });
        }

        // Add is_delete to require table (applicant)
        if (! Schema::hasColumn('require', 'is_delete')) {
            Schema::table('require', function (Blueprint $table) {
                $table->boolean('is_delete')->default(false)->index()->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop is_delete from users table
        if (Schema::hasColumn('users', 'is_delete')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_delete');
            });
        }

        // Drop is_delete from require table
        if (Schema::hasColumn('require', 'is_delete')) {
            Schema::table('require', function (Blueprint $table) {
                $table->dropColumn('is_delete');
            });
        }
    }
};
