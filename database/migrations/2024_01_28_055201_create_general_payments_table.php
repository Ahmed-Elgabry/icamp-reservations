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
        Schema::create('general_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('payment_method')->nullable();
            $table->string('statement')->nullable();
            $table->text('notes')->nullable();
            $table->enum('verified', [0, 1])->default(0);
            $table->string('image_path')->nullable();

            $table->unsignedBigInteger('account_id')->nullable(); // ID of the sender account
            $table->foreign('account_id')->references('id')->on('bank_accounts')->onDelete('set null');
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
        Schema::dropIfExists('general_payments');
    }
};
