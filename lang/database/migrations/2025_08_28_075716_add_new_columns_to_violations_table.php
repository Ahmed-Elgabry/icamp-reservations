<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->date('violation_date')->nullable()->after('employee_id');
            $table->time('violation_time')->nullable()->after('violation_date');
            $table->string('violation_place')->nullable()->after('violation_time');
            $table->string('photo_path')->nullable()->after('violation_place');
        });
    }

    public function down()
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn(['violation_date', 'violation_time', 'violation_place', 'photo_path']);
        });
    }
};
