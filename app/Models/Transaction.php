<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'account_id',
        'rab_id',
        'expense_location_id',
        'type',
        'amount',
        'description',
        'date',
        'invoice_id',
        'payable_id',
        'source_type',
        'evidence_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class);
    }

    public function payable(): BelongsTo
    {
        return $this->belongsTo(Payable::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(ExpenseLocation::class, 'expense_location_id');
    }
}
