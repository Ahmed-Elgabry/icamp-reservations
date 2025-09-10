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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('additional_notes')->nullable()->after('report_text');
            $table->boolean('show_price')->default(false)->after('additional_notes');
            $table->boolean('order_data')->default(false)->after('show_price');
            $table->boolean('invoice')->default(false)->after('order_data');
            $table->boolean('receipt')->default(false)->after('invoice');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['additional_notes', 'show_price', 'order_data', 'invoice', 'receipt']);
        });
    }
};
