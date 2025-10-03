<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('order_assets', function (Blueprint $table) {
            // ✅ أعمدة تسجيل الخروج
            $table->string('checkout_video_path')->nullable()->after('checkin_notes');
            $table->string('checkout_audio_path')->nullable()->after('checkout_video_path');
            $table->string('checkout_image_path')->nullable()->after('checkout_audio_path');
            $table->text('checkout_notes')->nullable()->after('checkout_image_path');
        });
    }

    public function down()
    {
        Schema::table('order_assets', function (Blueprint $table) {
            $table->dropColumn([
                'checkout_video_path',
                'checkout_audio_path',
                'checkout_image_path',
                'checkout_notes',
            ]);
        });
    }
};
