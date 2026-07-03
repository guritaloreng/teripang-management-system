<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    protected $fillable = [

        'purchase_id',

        'sea_cucumber_type_id',

        'purchase_weight',

        'price_per_kg',

        'subtotal'

    ];

    protected $casts = [

        'purchase_weight' => 'decimal:2',

        'price_per_kg' => 'decimal:2',

        'subtotal' => 'decimal:2'

    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            SeaCucumberType::class,
            'sea_cucumber_type_id'
        );
    }
}