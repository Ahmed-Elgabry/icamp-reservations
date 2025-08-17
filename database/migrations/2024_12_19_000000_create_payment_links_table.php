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
        Schema::create('payment_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->string('checkout_id')->unique(); // Paymennt Checkout ID
            $table->string('checkout_key')->nullable(); // Paymennt Checkout Key
            $table->text('payment_url'); // رابط الدفع
            $table->enum('status', ['pending', 'paid', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('request_id')->nullable(); // Paymennt Request ID
            $table->string('order_id_paymennt')->nullable(); // Paymennt Order ID
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index(['customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_links');
    }
};
