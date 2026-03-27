<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('rabs', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('rab_items', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('payables', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('rabs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('rab_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('payables', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
