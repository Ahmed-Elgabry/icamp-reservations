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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            
            $table->unsignedBigInteger('receiver_id')->nullable(); // ID of the receiver account
            $table->unsignedBigInteger('customer_id')->nullable(); // ID of the customer (if applicable)
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_addon_id')->nullable();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->date('date')->nullable();
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->text('description')->nullable(); // Description of the transaction
            $table->string('source')->nullable();
            $table->enum('verified', [0, 1])->default(0);
            
            // Foreign key constraints
            $table->unsignedBigInteger('account_id')->nullable(); // ID of the sender account
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('set null');
            $table->foreign('order_addon_id')->references('id')->on('order_addons')->onDelete('set null');
            $table->foreign('account_id')->references('id')->on('bank_accounts')->onDelete('set null');
            $table->foreign('receiver_id')->references('id')->on('bank_accounts')->onDelete('set null');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');


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
        Schema::dropIfExists('transactions');
    }
};
