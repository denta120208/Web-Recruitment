<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * We'll make the target columns TEXT to safely store encrypted payloads.
     */
    public function up(): void
    {
        Schema::table('Require', function (Blueprint $table) {
            // If your table is named differently, adapt accordingly.
            // Make columns nullable and TEXT to fit encrypted strings.
            if (Schema::hasColumn('Require', 'DateOfBirth')) {
                $table->text('DateOfBirth')->nullable()->change();
            }
            if (Schema::hasColumn('Require', 'Gmail')) {
                $table->text('Gmail')->nullable()->change();
            }
            if (Schema::hasColumn('Require', 'Phone')) {
                $table->text('Phone')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Require', function (Blueprint $table) {
            // Revert to varchar(255) - adjust if your original type was different
            if (Schema::hasColumn('Require', 'DateOfBirth')) {
                $table->string('DateOfBirth', 255)->nullable()->change();
            }
            if (Schema::hasColumn('Require', 'Gmail')) {
                $table->string('Gmail', 255)->nullable()->change();
            }
            if (Schema::hasColumn('Require', 'Phone')) {
                $table->string('Phone', 255)->nullable()->change();
            }
        });
    }
};
