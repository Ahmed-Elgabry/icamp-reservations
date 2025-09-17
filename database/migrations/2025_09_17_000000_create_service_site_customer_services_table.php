<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_site_customer_services', function (Blueprint $table) {
            $table->id();
            $table->longText('serviceSite')->nullable();
            $table->longText('workername_en')->nullable();
            $table->longText('workername_ar')->nullable();
            $table->string('workerphone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_site_customer_services');
    }
};
