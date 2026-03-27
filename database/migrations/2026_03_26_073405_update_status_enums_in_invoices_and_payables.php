<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'partial' to invoices status enum
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid'");
        
        // Add 'partial' to payables status enum
        DB::statement("ALTER TABLE payables MODIFY COLUMN status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum (note: this might fail if 'partial' data exists)
        DB::statement("ALTER TABLE invoices MODIFY COLUMN status ENUM('unpaid', 'paid') DEFAULT 'unpaid'");
        DB::statement("ALTER TABLE payables MODIFY COLUMN status ENUM('unpaid', 'paid') DEFAULT 'unpaid'");
    }
};
