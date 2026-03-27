<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Make account_id nullable so imports can leave it blank
            $table->foreignId('account_id')->nullable()->change();
            // Make type nullable, default to null for draft imports
            $table->enum('type', ['income', 'expense'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable(false)->change();
            $table->enum('type', ['income', 'expense'])->nullable(false)->change();
        });
    }
};
