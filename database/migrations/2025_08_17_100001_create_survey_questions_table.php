<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id');
            $table->string('question_text');
            $table->string('question_type');
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->string('validation_type')->default('none');
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->text('error_message')->nullable();
            $table->json('options')->nullable(); // For multiple choice, checkbox, etc.
            $table->json('settings')->nullable(); // For additional settings like star count, emoji options, etc.
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_questions');
    }
};
