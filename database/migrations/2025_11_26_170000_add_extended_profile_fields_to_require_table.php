<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('require', function (Blueprint $table) {
            if (! Schema::hasColumn('require', 'marital_status')) {
                $table->string('marital_status', 50)->nullable()->after('gender');
            }

            if (! Schema::hasColumn('require', 'is_fresh_graduate')) {
                $table->boolean('is_fresh_graduate')->default(false)->after('marital_status');
            }

            if (! Schema::hasColumn('require', 'ref1_name')) {
                $table->string('ref1_name')->nullable()->after('photopath');
                $table->string('ref1_address_phone')->nullable()->after('ref1_name');
                $table->string('ref1_occupation')->nullable()->after('ref1_address_phone');
                $table->string('ref1_relationship')->nullable()->after('ref1_occupation');

                $table->string('ref2_name')->nullable()->after('ref1_relationship');
                $table->string('ref2_address_phone')->nullable()->after('ref2_name');
                $table->string('ref2_occupation')->nullable()->after('ref2_address_phone');
                $table->string('ref2_relationship')->nullable()->after('ref2_occupation');

                $table->string('ref3_name')->nullable()->after('ref2_relationship');
                $table->string('ref3_address_phone')->nullable()->after('ref3_name');
                $table->string('ref3_occupation')->nullable()->after('ref3_address_phone');
                $table->string('ref3_relationship')->nullable()->after('ref3_occupation');
            }

            if (! Schema::hasColumn('require', 'emergency1_name')) {
                $table->string('emergency1_name')->nullable()->after('ref3_relationship');
                $table->string('emergency1_address')->nullable()->after('emergency1_name');
                $table->string('emergency1_phone')->nullable()->after('emergency1_address');
                $table->string('emergency1_relationship')->nullable()->after('emergency1_phone');

                $table->string('emergency2_name')->nullable()->after('emergency1_relationship');
                $table->string('emergency2_address')->nullable()->after('emergency2_name');
                $table->string('emergency2_phone')->nullable()->after('emergency2_address');
                $table->string('emergency2_relationship')->nullable()->after('emergency2_phone');
            }

            if (! Schema::hasColumn('require', 'q11_willing_outside_jakarta')) {
                $table->boolean('q11_willing_outside_jakarta')->nullable()->after('emergency2_relationship');
            }

            if (! Schema::hasColumn('require', 'q14_current_income')) {
                $table->text('q14_current_income')->nullable()->after('q11_willing_outside_jakarta');
            }

            if (! Schema::hasColumn('require', 'q15_expected_income')) {
                $table->text('q15_expected_income')->nullable()->after('q14_current_income');
            }

            if (! Schema::hasColumn('require', 'q16_available_from')) {
                $table->string('q16_available_from')->nullable()->after('q15_expected_income');
            }
        });
    }

    public function down(): void
    {
        Schema::table('require', function (Blueprint $table) {
            if (Schema::hasColumn('require', 'q16_available_from')) {
                $table->dropColumn('q16_available_from');
            }
            if (Schema::hasColumn('require', 'q15_expected_income')) {
                $table->dropColumn('q15_expected_income');
            }
            if (Schema::hasColumn('require', 'q14_current_income')) {
                $table->dropColumn('q14_current_income');
            }
            if (Schema::hasColumn('require', 'q11_willing_outside_jakarta')) {
                $table->dropColumn('q11_willing_outside_jakarta');
            }
            if (Schema::hasColumn('require', 'emergency2_relationship')) {
                $table->dropColumn([
                    'emergency1_name', 'emergency1_address', 'emergency1_phone', 'emergency1_relationship',
                    'emergency2_name', 'emergency2_address', 'emergency2_phone', 'emergency2_relationship',
                ]);
            }
            if (Schema::hasColumn('require', 'ref3_relationship')) {
                $table->dropColumn([
                    'ref1_name', 'ref1_address_phone', 'ref1_occupation', 'ref1_relationship',
                    'ref2_name', 'ref2_address_phone', 'ref2_occupation', 'ref2_relationship',
                    'ref3_name', 'ref3_address_phone', 'ref3_occupation', 'ref3_relationship',
                ]);
            }
            if (Schema::hasColumn('require', 'is_fresh_graduate')) {
                $table->dropColumn('is_fresh_graduate');
            }
            if (Schema::hasColumn('require', 'marital_status')) {
                $table->dropColumn('marital_status');
            }
        });
    }
};
