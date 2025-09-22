<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manual_whatsapp_sends', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Message title for display purposes
            $table->unsignedBigInteger('template_id'); // WhatsApp template used
            $table->json('customer_ids'); // Array of customer IDs selected
            $table->json('manual_numbers'); // Array of manual phone numbers
            $table->json('attachments')->nullable(); // Array of file paths for attachments
            $table->text('custom_message')->nullable(); // Custom message content if any
            $table->enum('status', ['pending', 'sending', 'completed', 'failed'])->default('pending');
            $table->json('send_results')->nullable(); // Results of each send attempt
            $table->unsignedBigInteger('sent_count')->default(0);
            $table->unsignedBigInteger('failed_count')->default(0);
            $table->unsignedBigInteger('total_count')->default(0);
            $table->text('error_message')->nullable();
            $table->unsignedBigInteger('created_by'); // User who initiated the send
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('whatsapp_message_templates')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_whatsapp_sends');
    }
};