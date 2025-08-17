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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('voice_note_logout')->after('refunds_notes')->nullable();
            $table->string('video_note_logout')->after('voice_note_logout')->nullable();
            $table->text('terms_notes')->after('video_note_logout')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('voice_note_logout');
            $table->dropColumn('video_note_logout');
            $table->dropColumn('terms_notes');
        });
    }
};
