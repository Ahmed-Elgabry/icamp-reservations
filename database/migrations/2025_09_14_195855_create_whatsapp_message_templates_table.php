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
        Schema::create('whatsapp_message_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Template name for admin identification
            $table->string('type',191); // show_price, invoice, receipt, reservation_data, evaluation
            $table->text('message_ar'); // Arabic message content (HTML from CKEditor)
            $table->text('message_en'); // English message content (HTML from CKEditor)
            $table->boolean('is_active')->default(true); // To enable/disable templates
            $table->text('description')->nullable(); // Optional description for admin
            $table->timestamps();
            
            // Add index for faster queries
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('whatsapp_message_templates');
    }
};
