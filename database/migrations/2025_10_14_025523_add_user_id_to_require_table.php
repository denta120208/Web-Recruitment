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
            $table->unsignedBigInteger('user_id')->nullable()->after('reviewed_by');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id', 'require_user_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Require', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex('require_user_id_idx');
            $table->dropColumn('user_id');
        });
    }
};
