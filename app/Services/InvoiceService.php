<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class InvoiceService
{
    /**
     * Create or update an invoice.
     */
    public function store(array $data, ?int $id = null): Invoice
    {
        return DB::transaction(function () use ($data, $id) {
            $invoice = Invoice::updateOrCreate(['id' => $id], $data);
            $this->clearDashboardCache();
            return $invoice;
        });
    }

    /**
     * Mark invoice as paid — creates income transaction atomically.
     */
    public function markAsPaid(int $invoiceId): bool
    {
        return DB::transaction(function () use ($invoiceId) {
            $invoice = Invoice::findOrFail($invoiceId);

            if ($invoice->isPaid()) {
                return false;
            }

            // Auto-create income transaction
            $account = Account::first();
            $category = Category::where('type', 'income')->first();

            if (!$account) {
                throw new \Exception('Silakan buat akun keuangan terlebih dahulu.');
            }

            Transaction::create([
                'account_id' => $account->id,
                'type' => 'income',
                'amount' => $invoice->remaining_amount,
                'category_id' => $category?->id,
                'source_type' => 'B2B',
                'description' => 'Pembayaran invoice: ' . $invoice->client_name,
                'date' => now()->toDateString(),
                'invoice_id' => $invoice->id,
            ]);

            $invoice->refresh();
            $invoice->update(['status' => $invoice->payment_status]);
            
            $this->clearDashboardCache();

            return true;
        });
    }

    /**
     * Delete an invoice.
     */
    public function delete(int $id): bool
    {
        $invoice = Invoice::findOrFail($id);
        $deleted = $invoice->delete();
        $this->clearDashboardCache();
        return $deleted;
    }

    /**
     * Clear financial dashboard cache.
     */
    public function clearDashboardCache(): void
    {
        Cache::forget('finance_health_score');
        Cache::forget('finance_health_insights');
    }
}
