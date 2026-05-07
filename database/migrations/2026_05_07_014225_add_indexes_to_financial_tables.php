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
            $table->index(['date', 'type']);
            $table->index('source_type');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('status');
            $table->index('due_date');
        });

        Schema::table('payables', function (Blueprint $table) {
            $table->index('status');
            $table->index('due_date');
            $table->index('promise_to_pay_date');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['date', 'type']);
            $table->dropIndex(['source_type']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
        });

        Schema::table('payables', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['due_date']);
            $table->dropIndex(['promise_to_pay_date']);
        });
    }
};
