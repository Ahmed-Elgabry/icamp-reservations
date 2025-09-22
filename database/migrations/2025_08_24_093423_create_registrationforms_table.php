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
        Schema::create('registrationforms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            $table->string('request_code', 20)->unique();
            $table->date('booking_date');
            $table->string('time_slot');
            $table->time('checkin_time')->nullable();
            $table->time('checkout_time')->nullable();
            $table->boolean('terms_accepted')->default(false);
            $table->unsignedInteger('persons');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_phone', 32);
            $table->string('email', 255);
            $table->text('notes')->nullable();

            $table->index(['service_id', 'request_code']);
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
        Schema::dropIfExists('registrationforms');
    }
};
