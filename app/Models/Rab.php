<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rab extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'start_date', 'end_date', 'total_budget', 'description'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_budget' => 'decimal:2',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RabItem::class);
    }

    public function getUsedBudgetAttribute(): float
    {
        return (float) $this->transactions()->where('type', 'expense')->sum('amount');
    }

    public function getRemainingBudgetAttribute(): float
    {
        return (float) $this->total_budget - $this->used_budget;
    }

    public function getUsagePercentAttribute(): float
    {
        if ($this->total_budget <= 0) return 0;
        return round(($this->used_budget / $this->total_budget) * 100, 1);
    }
}
