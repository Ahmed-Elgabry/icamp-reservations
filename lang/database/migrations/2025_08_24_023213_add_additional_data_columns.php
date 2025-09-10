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
            // Remove the old columns first
            $table->dropColumn([
                'additional_notes',
                'show_price',
                'order_data',
                'invoice',
                'receipt'
            ]);

            // Add the new columns
            $table->text('show_price_notes')->nullable()->after('report_text');
            $table->text('order_data_notes')->nullable()->after('show_price_notes');
            $table->text('invoice_notes')->nullable()->after('order_data_notes');
            $table->text('receipt_notes')->nullable()->after('invoice_notes');
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
            // Remove the new columns
            $table->dropColumn([
                'show_price_notes',
                'order_data_notes',
                'invoice_notes',
                'receipt_notes'
            ]);

            // Add back the old columns
            $table->text('additional_notes')->nullable()->after('report_text');
            $table->tinyInteger('show_price')->default(0)->after('additional_notes');
            $table->tinyInteger('order_data')->default(0)->after('show_price');
            $table->tinyInteger('invoice')->default(0)->after('order_data');
            $table->tinyInteger('receipt')->default(0)->after('invoice');
        });
    }
};
