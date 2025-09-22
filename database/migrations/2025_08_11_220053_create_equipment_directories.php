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
        // Main directories table
        Schema::create('equipment_directories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Directory items table
        Schema::create('equipment_directory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('directory_id')->constrained('equipment_directories');
            $table->string('type');
            $table->string('name');
            $table->string('location');
            $table->integer('quantity')->default(1);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // Item media table
        Schema::create('equipment_directory_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('equipment_directory_items');
            $table->string('file_path');
            $table->string('file_type'); // 'image' or 'video'
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
        Schema::dropIfExists('equipment_directory_media');
        Schema::dropIfExists('equipment_directory_items');
        Schema::dropIfExists('equipment_directories');
    }
};
