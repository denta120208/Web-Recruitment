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
            $table->enum('status', ['pending', 'under_review', 'interview_scheduled', 'accepted', 'rejected', 'hired'])->default('pending')->after('Phone');
            $table->text('admin_notes')->nullable()->after('status');
            $table->timestamp('status_updated_at')->nullable()->after('admin_notes');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('status_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Require', function (Blueprint $table) {
            $table->dropColumn(['status', 'admin_notes', 'status_updated_at', 'reviewed_by']);
        });
    }
};
