<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class TransactionService
{
    /**
     * Create or update a transaction.
     */
    public function store(array $data, $id = null): Transaction
    {
        return DB::transaction(function () use ($data, $id) {
            if ($id) {
                $transaction = Transaction::findOrFail($id);
                
                // Handle evidence replacement if new one provided
                if (isset($data['evidence_path']) && $transaction->evidence_path) {
                    Storage::disk('public')->delete($transaction->evidence_path);
                }
                
                $transaction->update($data);
            } else {
                $transaction = Transaction::create($data);
            }

            // Clear dashboard cache
            $this->clearDashboardCache();

            return $transaction;
        });
    }

    /**
     * Soft delete a transaction.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = Transaction::findOrFail($id);
            $deleted = $transaction->delete();
            $this->clearDashboardCache();
            return $deleted;
        });
    }

    /**
     * Permanently delete a transaction.
     */
    public function forceDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = Transaction::onlyTrashed()->findOrFail($id);
            
            if ($transaction->evidence_path) {
                Storage::disk('public')->delete($transaction->evidence_path);
            }
            
            $deleted = $transaction->forceDelete();
            $this->clearDashboardCache();
            return $deleted;
        });
    }

    /**
     * Restore a soft-deleted transaction.
     */
    public function restore(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $transaction = Transaction::onlyTrashed()->findOrFail($id);
            $restored = $transaction->restore();
            $this->clearDashboardCache();
            return $restored;
        });
    }

    /**
     * Clear financial health score cache.
     */
    public function clearDashboardCache(): void
    {
        Cache::forget('finance_health_score');
        Cache::forget('finance_health_insights');
    }
}
