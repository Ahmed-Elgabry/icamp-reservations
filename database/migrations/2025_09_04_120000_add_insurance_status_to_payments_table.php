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
        if (!Schema::hasColumn('payments', 'insurance_status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('insurance_status', ['returned', 'confiscated_full', 'confiscated_partial'])
                    ->nullable()
                    ->after('statement')
                    ->comment('Insurance status for insurance payments');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('insurance_status');
        });
    }
};
