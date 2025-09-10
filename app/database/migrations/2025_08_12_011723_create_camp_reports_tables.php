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
        Schema::create('camp_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->string('camp_name')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->text('general_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('camp_report_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('camp_report_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('video_path')->nullable();
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
        Schema::dropIfExists('camp_reports');
        Schema::dropIfExists('camp_report_items');
    }
};
