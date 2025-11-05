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
            $table->integer('year')->nullable()->after('major'); // Tahun lulus
            $table->decimal('score', 5, 2)->nullable()->after('year'); // IPK/Score (bisa koma)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requireeducation', function (Blueprint $table) {
            $table->dropColumn(['year', 'score']);
        });
    }
};
