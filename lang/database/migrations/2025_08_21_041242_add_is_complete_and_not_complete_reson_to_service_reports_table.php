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
        Schema::table('service_reports', function (Blueprint $table) {
            $table->boolean('is_completed')->default(false)->after('service_id');
            $table->string('not_completed_reason')->nullable()->after('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_reports', function (Blueprint $table) {
            $table->dropColumn('is_completed');
            $table->dropColumn('not_completed_reason');
        });
    }
};
