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
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->engine = "InnoDB";
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('stock_id')->constrained()->cascadeOnDelete();
            $t->decimal('quantity', 12, 3);
            $t->decimal('total_price', 12, 2);
            $t->text('notes')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
