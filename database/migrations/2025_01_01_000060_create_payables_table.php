<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->decimal('total', 15, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('due_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('payable_id')->nullable()->after('rab_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payable_id');
        });

        Schema::dropIfExists('payables');
    }
};
