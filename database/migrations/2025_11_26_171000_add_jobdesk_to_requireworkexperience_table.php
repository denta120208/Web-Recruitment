<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requireworkexperience', function (Blueprint $table) {
            if (! Schema::hasColumn('requireworkexperience', 'jobdesk')) {
                $table->text('jobdesk')->nullable()->after('eexp_comments');
            }
        });
    }

    public function down(): void
    {
        Schema::table('requireworkexperience', function (Blueprint $table) {
            if (Schema::hasColumn('requireworkexperience', 'jobdesk')) {
                $table->dropColumn('jobdesk');
            }
        });
    }
};
