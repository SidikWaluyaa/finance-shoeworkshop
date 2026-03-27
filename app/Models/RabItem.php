<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RabItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['rab_id', 'name', 'amount', 'description'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function rab(): BelongsTo
    {
        return $this->belongsTo(Rab::class);
    }
}
