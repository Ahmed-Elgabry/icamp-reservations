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
            if (Schema::hasColumn('orders','agree')) {
                $table->dropColumn('agree');
            }
            if (!Schema::hasColumn('orders','signature_path')) {
                $table->string('signature_path')->nullable();
            }
            $table->string('signature')->nullable()->after('status');
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
            $table->string('agree')->nullable()->after('status');
        });
    }
};
