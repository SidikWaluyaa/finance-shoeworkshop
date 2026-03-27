<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Rab;
use App\Models\Transaction;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        // Accounts
        $bankBCA = Account::create(['name' => 'BCA Business', 'type' => 'bank', 'balance' => 0]);
        $cash = Account::create(['name' => 'Kas Toko', 'type' => 'cash', 'balance' => 0]);
        $ovo = Account::create(['name' => 'OVO Business', 'type' => 'e-wallet', 'balance' => 0]);

        // Categories - Income
        $catSalesB2B = Category::create(['name' => 'Penjualan B2B', 'type' => 'income']);
        $catSalesB2C = Category::create(['name' => 'Penjualan B2C', 'type' => 'income']);
        $catService = Category::create(['name' => 'Jasa Reparasi', 'type' => 'income']);

        // Categories - Expense
        $catMaterial = Category::create(['name' => 'Bahan Baku', 'type' => 'expense']);
        $catOps = Category::create(['name' => 'Operasional', 'type' => 'expense']);
        $catSalary = Category::create(['name' => 'Gaji Karyawan', 'type' => 'expense']);

        // RABs
        $rabQ1 = Rab::create([
            'name' => 'RAB Q1 2025 - Operasional',
            'total_budget' => 15000000,
            'description' => 'Anggaran operasional kuartal 1',
        ]);
        $rabMaterial = Rab::create([
            'name' => 'RAB Bahan Baku Maret',
            'total_budget' => 8000000,
            'description' => 'Anggaran pembelian bahan baku bulan Maret',
        ]);

        // Invoices
        $inv1 = Invoice::create([
            'client_name' => 'PT Maju Jaya',
            'total' => 5000000,
            'status' => 'paid',
            'due_date' => Carbon::now()->subDays(10),
        ]);
        $inv2 = Invoice::create([
            'client_name' => 'CV Sepatu Keren',
            'total' => 3500000,
            'status' => 'unpaid',
            'due_date' => Carbon::now()->addDays(7),
        ]);
        $inv3 = Invoice::create([
            'client_name' => 'Toko Abadi Sport',
            'total' => 2000000,
            'status' => 'unpaid',
            'due_date' => Carbon::now()->subDays(3),
        ]);

        // Transactions - spread across 6 months
        $months = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));

        foreach ($months as $month) {
            // B2B income
            Transaction::create([
                'account_id' => $bankBCA->id,
                'type' => 'income',
                'amount' => rand(4000000, 8000000),
                'category_id' => $catSalesB2B->id,
                'source_type' => 'B2B',
                'description' => 'Penjualan B2B ' . $month->format('F Y'),
                'date' => $month->copy()->day(rand(1, 15)),
            ]);

            // B2C income
            Transaction::create([
                'account_id' => $cash->id,
                'type' => 'income',
                'amount' => rand(2000000, 4000000),
                'category_id' => $catSalesB2C->id,
                'source_type' => 'B2C',
                'description' => 'Penjualan retail ' . $month->format('F Y'),
                'date' => $month->copy()->day(rand(5, 20)),
            ]);

            // Service income
            Transaction::create([
                'account_id' => $ovo->id,
                'type' => 'income',
                'amount' => rand(500000, 1500000),
                'category_id' => $catService->id,
                'source_type' => 'B2C',
                'description' => 'Jasa reparasi ' . $month->format('F Y'),
                'date' => $month->copy()->day(rand(10, 25)),
            ]);

            // Expense - materials (linked to RAB)
            Transaction::create([
                'account_id' => $bankBCA->id,
                'type' => 'expense',
                'amount' => rand(1500000, 3000000),
                'category_id' => $catMaterial->id,
                'source_type' => 'B2B',
                'description' => 'Pembelian bahan baku ' . $month->format('F Y'),
                'date' => $month->copy()->day(rand(1, 10)),
                'rab_id' => $rabMaterial->id,
            ]);

            // Expense - operations (linked to RAB)
            Transaction::create([
                'account_id' => $cash->id,
                'type' => 'expense',
                'amount' => rand(1000000, 2500000),
                'category_id' => $catOps->id,
                'source_type' => 'B2C',
                'description' => 'Biaya operasional ' . $month->format('F Y'),
                'date' => $month->copy()->day(rand(15, 28)),
                'rab_id' => $rabQ1->id,
            ]);

            // Expense - salary
            Transaction::create([
                'account_id' => $bankBCA->id,
                'type' => 'expense',
                'amount' => 3000000,
                'category_id' => $catSalary->id,
                'source_type' => 'B2B',
                'description' => 'Gaji karyawan ' . $month->format('F Y'),
                'date' => $month->copy()->day(25),
            ]);
        }

        // Invoice paid transaction
        Transaction::create([
            'account_id' => $bankBCA->id,
            'type' => 'income',
            'amount' => $inv1->total,
            'category_id' => $catSalesB2B->id,
            'source_type' => 'B2B',
            'description' => 'Pembayaran invoice ' . $inv1->client_name,
            'date' => Carbon::now()->subDays(5),
            'invoice_id' => $inv1->id,
        ]);
    }
}
