<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('country_code')->default('+966');
            $table->string('phone')->unique()->nullable();
            $table->boolean('is_email_verified')->default(0);
            $table->boolean('is_phone_verified')->default(0);
            $table->string('password')->nullable();
            $table->string('job')->nullable();
            $table->rememberToken();
            $table->bigInteger('user_type')->index()->unsigned()->default(2);
            $table->string('image')->nullable();
            $table->foreign('user_type')->references('id')->on('user_types');
            $table->boolean('is_active')->default(0);
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_notify')->default(1);
            $table->string('lang')->default('ar');


            $table->string('code', 10)->nullable();
            $table->timestamp('code_expire')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 10, 8)->nullable();
            $table->string('address', 50)->nullable();
            $table->decimal('wallet_balance', 9,2)->default(0);
      
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('identity_image')->nullable();
            $table->string('front_car_image')->nullable();
            $table->string('back_car_image')->nullable();
            $table->string('car_license')->nullable();
            $table->string('driver_license')->nullable();
            $table->string('car_image')->nullable();
            $table->timestamps();
        });


      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
