<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $fillable = [

        'name',

        'phone',

        'note'

    ];

    public function ledgers(): HasMany
    {
        return $this->hasMany(InvestorLedger::class);
    }
}