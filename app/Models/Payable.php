<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property \Carbon\Carbon $due_date
 * @property \Carbon\Carbon|null $promise_to_pay_date
 * @property string $status
 * @property float $total
 */
class Payable extends Model
{
    use SoftDeletes;

    protected $fillable = ['supplier_name', 'total', 'status', 'due_date', 'promise_to_pay_date', 'description'];

    protected $casts = [
        'total' => 'decimal:2',
        'due_date' => 'date',
        'promise_to_pay_date' => 'date',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->transactions()->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->total - $this->paid_amount);
    }

    public function getPaymentStatusAttribute(): string
    {
        $paid = $this->paid_amount;
        if ($paid <= 0) return 'unpaid';
        if ($paid < $this->total) return 'partial';
        return 'paid';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isOverdue(): bool
    {
        return !$this->isPaid() && $this->due_date->isPast();
    }
}
