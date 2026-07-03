<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestorLedger extends Model
{
    protected $fillable = [

        'investor_id',

        'transaction_date',

        'transaction_type',

        'amount',

        'note',

    ];

    protected $casts = [

        'transaction_date'=>'date',

        'amount'=>'decimal:2'

    ];

    public function investor(): BelongsTo
    {
        return $this->belongsTo(Investor::class);
    }
}