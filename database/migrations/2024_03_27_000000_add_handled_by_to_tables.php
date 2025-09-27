<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add handled_by to payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('handled_by')->nullable()->constrained('users');
            $table->foreignId('insurance_handled_by')->nullable()->constrained('users');
        });

        // Add handled_by to orders table for insurance handling
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('insurance_handled_by')->nullable()->constrained('users');
        });

        // Add handled_by to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('handled_by')->nullable()->constrained('users');
        });

        // Add handled_by to expenses table
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('handled_by')->nullable()->constrained('users');
        });

        // Add handled_by to general_payments table
        Schema::table('general_payments', function (Blueprint $table) {
            $table->foreignId('handled_by')->nullable()->constrained('users');
        });

        // Add handled_by to payment_links table
        Schema::table('payment_links', function (Blueprint $table) {
            $table->foreignId('handled_by')->nullable()->constrained('users');
        });

        // Add handled_by to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('handled_by')->nullable()->constrained('users');
            $table->foreignId('insurance_handled_by')->nullable()->constrained('users');
        });

        
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn('handled_by');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['insurance_handled_by']);
            $table->dropColumn('insurance_handled_by');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn('handled_by');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn('handled_by');
        });

        Schema::table('general_payments', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn('handled_by');
        });

        Schema::table('payment_links', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn('handled_by');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['handled_by']);
            $table->dropColumn('handled_by');
        });
    }
};
