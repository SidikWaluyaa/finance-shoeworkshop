<?php

namespace App\Services;

use App\Models\Payable;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class PayableService
{
    /**
     * Create a new payable.
     */
    public function createPayable(array $data): Payable
    {
        $data['status'] = $data['status'] ?? 'unpaid';
        return Payable::create($data);
    }

    /**
     * Update an existing payable.
     */
    public function updatePayable(Payable $payable, array $data): Payable
    {
        $payable->update($data);
        return $payable->fresh();
    }

    /**
     * Delete a payable (only if unpaid).
     */
    public function deletePayable(Payable $payable): bool
    {
        if ($payable->isPaid()) {
            return false;
        }

        $payable->delete();
        return true;
    }

    /**
     * Mark payable as paid — creates expense transaction atomically.
     */
    public function markAsPaid(int $payableId, int $accountId, ?float $amount = null): Transaction
    {
        return DB::transaction(function () use ($payableId, $accountId, $amount) {
            $payable = Payable::findOrFail($payableId);

            if ($payable->isPaid()) {
                throw new \Exception('Payable sudah lunas.');
            }

            $amountToPay = $amount ?? $payable->remaining_amount;

            if ($amountToPay > $payable->remaining_amount) {
                throw new \Exception('Nilai pembayaran melebihi sisa utang.');
            }

            // Find expense category
            $category = Category::where('type', 'expense')->first();

            // Create expense transaction
            $transaction = Transaction::create([
                'account_id' => $accountId,
                'type' => 'expense',
                'amount' => $amountToPay,
                'category_id' => $category?->id,
                'source_type' => 'B2B',
                'description' => 'Pembayaran utang' . ($amount < $payable->total ? ' (Cicil)' : '') . ': ' . $payable->supplier_name,
                'date' => now()->toDateString(),
                'payable_id' => $payable->id,
            ]);

            // Update status based on resulting balance
            $payable->refresh();
            $payable->update(['status' => $payable->payment_status]);

            return $transaction;
        });
    }

    /**
     * Get total unpaid payables.
     */
    public function getTotalUnpaid(): float
    {
        return (float) Payable::where('status', 'unpaid')->sum('total');
    }

    /**
     * Get total paid payables.
     */
    public function getTotalPaid(): float
    {
        return (float) Payable::where('status', 'paid')->sum('total');
    }
}
