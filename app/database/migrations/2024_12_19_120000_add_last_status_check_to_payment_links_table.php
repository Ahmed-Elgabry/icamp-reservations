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
        Schema::table('payment_links', function (Blueprint $table) {
            $table->timestamp('last_status_check')->nullable()->after('order_id_paymennt');
            $table->index(['status', 'last_status_check']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_links', function (Blueprint $table) {
            $table->dropIndex(['status', 'last_status_check']);
            $table->dropColumn('last_status_check');
        });
    }
};
