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
        Schema::create('service_site_and_customer_services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText('service_site');
            $table->longText('workername_ar');
            $table->longText('workername_en');
            $table->string('workerphone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_site_and_customer_services');
    }
};
