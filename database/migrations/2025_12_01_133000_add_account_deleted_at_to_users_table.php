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
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'account_deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('account_deleted_at')->nullable()->after('is_delete');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'account_deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('account_deleted_at');
            });
        }
    }
};
