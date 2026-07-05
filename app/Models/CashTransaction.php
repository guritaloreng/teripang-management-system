<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [

        'transaction_date',

        'transaction_type',

        'reference_type',

        'reference_id',

        'description',

        'cash_in',

        'cash_out',

        'note',

    ];

    protected $casts = [

        'transaction_date' => 'date',

        'cash_in' => 'decimal:2',

        'cash_out' => 'decimal:2',

    ];
        /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isIncome(): bool
    {
        return $this->cash_in > 0;
    }

    public function isExpense(): bool
    {
        return $this->cash_out > 0;
    }

    public function amount(): float
    {
        return $this->cash_in > 0
            ? (float) $this->cash_in
            : (float) $this->cash_out;
    }
}    