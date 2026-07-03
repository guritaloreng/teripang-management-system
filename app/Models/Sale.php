<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [

        'shipment_id',

        'invoice_number',

        'sale_date',

        'buyer',

        'note'

    ];

    protected $casts = [

        'sale_date' => 'date'

    ];

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}