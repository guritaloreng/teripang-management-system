<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $fillable = [

        'purchase_number',

        'supplier_invoice',

        'purchase_date',

        'supplier_id',

        'grand_total',

        'photo',

        'note'

    ];

    protected $casts=[

        'purchase_date'=>'date',

        'grand_total'=>'decimal:2'

    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function getTotalWeightAttribute()
    {
        return $this->items->sum('purchase_weight');
    }
}