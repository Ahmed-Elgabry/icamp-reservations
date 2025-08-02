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
        Schema::create('order_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_report_id')->constrained('service_reports')->onDelete('cascade');
            $table->enum('is_completed', ['no_action', 'completed', 'not_completed'])->default('no_action');
            $table->text('not_completed_reason')->nullable();
            $table->text('notes')->nullable();

            
           
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
        Schema::dropIfExists('order_reports');
    }
};
