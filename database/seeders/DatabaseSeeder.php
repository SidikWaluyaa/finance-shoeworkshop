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
            RolePermissionSeeder::class,
        ]);

        // Master Data - Accounts (Initial Setup)
        Account::create(['name' => 'BCA Business', 'type' => 'bank', 'balance' => 0]);
        Account::create(['name' => 'Kas Toko', 'type' => 'cash', 'balance' => 0]);
        Account::create(['name' => 'OVO Business', 'type' => 'e-wallet', 'balance' => 0]);

        // Master Data - Categories (Standard Setup)
        Category::create(['name' => 'Penjualan', 'type' => 'income']);
        Category::create(['name' => 'Gaji', 'type' => 'expense']);
        Category::create(['name' => 'Operasional', 'type' => 'expense']);
    }
}
