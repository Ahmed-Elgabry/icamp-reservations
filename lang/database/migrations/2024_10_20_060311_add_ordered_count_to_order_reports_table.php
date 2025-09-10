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
        Schema::table('order_reports', function (Blueprint $table) {
            $table->integer('ordered_count')->nullable()->after('service_report_id');
            $table->integer('ordered_price')->nullable()->before('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_reports', function (Blueprint $table) {
            $table->dropColumn('ordered_count','ordered_price');
        });
    }
};
