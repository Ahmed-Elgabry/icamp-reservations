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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expense_item_id')->nullable();
            $table->foreign('expense_item_id')->references('id')->on('expense_items')->onDelete('set null')->onUpdate('cascade');
            $table->float('price', 8, 2);
            $table->date('date');
            $table->string('source')->nullable();
            $table->string('notes')->nullable();
            $table->string('statement')->nullable();        
            
            $table->unsignedBigInteger('account_id')->nullable(); // ID of the sender account
            $table->foreign('account_id')->references('id')->on('bank_accounts')->onDelete('set null');
            $table->unsignedBigInteger('order_id')->nullable(); // ID of the sender account
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
        Schema::dropIfExists('expenses');
    }
};
