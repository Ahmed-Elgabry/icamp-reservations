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
            $table->engine = "InnoDB";
            // reference ids
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('general_payment_id')->nullable();
            $table->unsignedBigInteger('stock_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();    // sender account
            $table->unsignedBigInteger('receiver_id')->nullable();   // receiver account
            $table->unsignedBigInteger('sender_account_id')->nullable();   // receiver account
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('order_addon_id')->nullable();

            // transaction data
            $table->date('date')->nullable();
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->enum('type', ['deposit', 'debit', 'transfer'])->nullable();
            $table->string('source')->nullable();
            $table->boolean('verified')->default(false);

            // foreign keys
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('cascade');
            $table->foreign('general_payment_id')->references('id')->on('general_payments')->onDelete('cascade');
            // order_addon pivot table is named 'order_addon' (singular)
            $table->foreign('order_addon_id')->references('id')->on('order_addon')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('bank_accounts')->onDelete('cascade');
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
