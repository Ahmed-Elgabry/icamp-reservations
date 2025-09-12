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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');
            // reference to stocks table (nullable in case stock was removed)
            $table->foreignId('stock_id')->nullable()->constrained('stocks')->nullOnDelete();
            // quantity deducted (can be positive integer)
            $table->integer('quantity')->unsigned();
            // type: item_decrement or item_increment
            $table->enum('type', ['item_decrement', 'item_increment'])->default(null);
            // optional reason and free-text custom reason
            $table->string('reason')->nullable();
            $table->string('custom_reason')->nullable();
            // optional note, image path, employee who performed the action
            $table->text('note')->nullable();
            $table->string('image')->nullable();
            $table->string('employee_name')->nullable();
            // optional reference to an order (nullable)
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            // the datetime when the adjustment occurred (useful for historic corrections)
            $table->dateTime('date_time')->nullable();

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
        Schema::dropIfExists('stock_adjustments');
    }
};
