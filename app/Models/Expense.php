<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [

        'expense_date',

        'shipment_id',

        'expense_name',

        'amount',

        'description',

        'note',

    ];

    protected $casts = [

        'expense_date' => 'date',

        'amount' => 'decimal:2',

    ];
        /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(
            Shipment::class
        );
    }   
    }