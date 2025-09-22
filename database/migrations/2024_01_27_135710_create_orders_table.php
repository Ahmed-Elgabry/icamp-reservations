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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
          //   $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 8, 2);
            $table->decimal('deposit', 8, 2)->default(0);
            $table->decimal('insurance_amount', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->date('date')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();


            $table->time('time_of_receipt')->nullable();
            $table->text('time_of_receipt_notes')->nullable();
            $table->time('delivery_time')->nullable();
            $table->text('delivery_time_notes')->nullable();
            $table->string('voice_note')->nullable();
            $table->string('video_note')->nullable();


            $table->string('image_before_receiving')->nullable();
            $table->string('image_after_delivery')->nullable();
            $table->enum('status', ['pending_and_show_price', 'pending_and_Initial_reservation', 'approved', 'canceled', 'delayed', 'completed'])->default('pending_and_show_price');
            $table->enum('refunds',[0,1])->default(0);
            $table->text('refunds_notes')->nullable();
            $table->time('delayed_time')->nullable();

            $table->enum('inventory_withdrawal',[0,1])->default(0);
            $table->enum('insurance_status', ['returned', 'confiscated_full', 'confiscated_partial'])->nullable();
            $table->text('confiscation_description')->nullable();
            $table->text('report_text')->nullable();


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
        Schema::dropIfExists('orders');
    }
};
