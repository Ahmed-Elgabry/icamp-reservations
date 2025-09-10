<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_response_id');
            $table->unsignedBigInteger('survey_question_id');
            $table->text('answer_text')->nullable();
            $table->json('answer_option')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('survey_response_id')->references('id')->on('survey_responses')->onDelete('cascade');
            $table->foreign('survey_question_id')->references('id')->on('survey_questions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_answers');
    }
};
