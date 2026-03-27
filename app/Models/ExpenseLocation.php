<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseLocation extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'address', 'description', 'icon'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
