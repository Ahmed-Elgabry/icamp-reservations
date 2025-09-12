<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->integer('available_quantity_before')->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropColumn('available_quantity_before');
        });
    }
};
