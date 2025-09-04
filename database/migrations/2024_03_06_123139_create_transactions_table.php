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
            $table->unsignedBigInteger('sender_account_id')->nullable(); // ID of the sender account
            $table->unsignedBigInteger('customer_id')->nullable(); // ID of the customer (if applicable)
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('general_payment_id')->nullable(); // ID of the general payment (if applicable)
            $table->unsignedBigInteger('order_addon_id')->nullable(); // ID of the addon (if applicable)
            $table->unsignedBigInteger('payment_id')->nullable(); // ID of the payment (if applicable)
            $table->unsignedBigInteger('expense_id')->nullable(); // ID of the expense (if applicable)
            $table->date('date')->nullable();
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->text('description')->nullable(); // Description of the transaction
            $table->enum('type', ['deposit', 'debit', 'transfer'])->nullable(); // Transaction type
            $table->string('source')->nullable();
            $table->boolean('verified')->default(false);
            // Foreign key constraints
            $table->unsignedBigInteger('account_id')->nullable(); // ID of the sender account
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->foreign('general_payment_id')->references('id')->on('general_payments')->onDelete("cascade");
            // order_addon pivot table is named 'order_addon' (singular)
            $table->foreign('order_addon_id')->references('id')->on('order_addon')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('sender_account_id')->references('id')->on('bank_accounts')->onDelete('cascade');

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
