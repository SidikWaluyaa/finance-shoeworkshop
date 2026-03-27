<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root to Dashboard (Protected)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Finance Dashboard & Features (Protected)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('pages.dashboard'))->name('dashboard');
    Route::get('/transactions', fn() => view('pages.transactions'))->name('transactions');
    Route::get('/invoices', fn() => view('pages.invoices'))->name('invoices');
    Route::get('/rabs', fn() => view('pages.rabs'))->name('rabs');
    Route::get('/payables', fn() => view('pages.payables'))->name('payables');
    Route::get('/expense-locations', fn() => view('pages.expense-locations'))->name('expense-locations');
    Route::get('/accounts', fn() => view('pages.accounts'))->name('accounts');
    Route::get('/categories', fn() => view('pages.categories'))->name('categories');
    Route::get('/users', fn() => view('pages.users'))->name('users');
    Route::get('/trash', fn() => view('pages.trash'))->name('trash');


    // Transaction Import Routes
    Route::get('/transactions/template', function() {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TransactionTemplateExport, 'template_transaksi.xlsx');
    })->name('transactions.template');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
