<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            // 1. Drop the old location column
            $table->dropColumn('location');

            // 2. Add new location_id column as foreign key
            $table->unsignedBigInteger('location_id')->after('id');
            $table->foreign('location_id')
                ->references('id')
                ->on('meeting_locations')
                ->onDelete('restrict'); // Prevent deletion if meetings exist
        });
    }

    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            // 1. Drop the foreign key first
            $table->dropForeign(['location_id']);

            // 2. Remove location_id column
            $table->dropColumn('location_id');

            // 3. Recreate the original location column
            $table->string('location')->after('meeting_number');
        });
    }
};
