<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stock_adjustments')) {
            Schema::table('stock_adjustments', function (Blueprint $table) {
                if (!Schema::hasColumn('stock_adjustments', 'available_percentage_before')) {
                    $table->decimal('available_percentage_before', 8, 2)->nullable()->after('available_quantity_before');
                }
                if (!Schema::hasColumn('stock_adjustments', 'percentage')) {
                    $table->decimal('percentage', 8, 2)->nullable()->after('available_percentage_before');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('stock_adjustments')) {
            Schema::table('stock_adjustments', function (Blueprint $table) {
                if (Schema::hasColumn('stock_adjustments', 'percentage')) {
                    $table->dropColumn('percentage');
                }
                if (Schema::hasColumn('stock_adjustments', 'available_percentage_before')) {
                    $table->dropColumn('available_percentage_before');
                }
            });
        }
    }
};
