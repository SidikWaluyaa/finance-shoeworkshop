<?php

namespace App\Services;

use App\Models\Rab;
use App\Models\RabItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RabService
{
    /**
     * Create or update a RAB with its items.
     */
    public function store(array $data, array $items, ?int $id = null): Rab
    {
        return DB::transaction(function () use ($data, $items, $id) {
            $rab = Rab::updateOrCreate(['id' => $id], $data);

            // Sync items: simplest way is to delete and recreate for this scale
            // or use a more sophisticated diffing if items have IDs and persistent data
            $rab->items()->delete();

            foreach ($items as $item) {
                $rab->items()->create([
                    'name' => $item['name'],
                    'amount' => $item['amount'],
                    'description' => $item['description'] ?? null,
                ]);
            }

            $this->clearDashboardCache();

            return $rab;
        });
    }

    /**
     * Soft delete a RAB.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $rab = Rab::findOrFail($id);
            
            // Soft delete the items too if they have the trait
            $rab->items()->delete();
            
            $deleted = $rab->delete();
            $this->clearDashboardCache();
            return $deleted;
        });
    }

    /**
     * Permanently delete a RAB.
     */
    public function forceDelete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $rab = Rab::onlyTrashed()->findOrFail($id);
            
            // Force delete items
            $rab->items()->forceDelete();
            
            $deleted = $rab->forceDelete();
            $this->clearDashboardCache();
            return $deleted;
        });
    }

    /**
     * Restore a soft-deleted RAB.
     */
    public function restore(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $rab = Rab::onlyTrashed()->findOrFail($id);
            
            // Restore items
            $rab->items()->restore();
            
            $restored = $rab->restore();
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
