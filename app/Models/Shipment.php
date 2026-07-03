<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $fillable = [

        'shipment_number',

        'shipment_date',

        'destination',

        'status',

        'shipping_cost',

        'note'

    ];

    protected $casts = [

        'shipment_date' => 'date',

        'shipping_cost' => 'decimal:2'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ATTRIBUTE
    |--------------------------------------------------------------------------
    */

    public function getPurchaseCountAttribute()
    {
        return $this->items()->count();
    }

    public function getInvoiceCountAttribute()
    {
        return $this->sales()->count();
    }

    public function getShippingCostFormattedAttribute()
    {
        return number_format(
            $this->shipping_cost,
            0,
            ',',
            '.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public function markArrived(): void
    {
        $this->update([
            'status' => 'Sampai Gudang'
        ]);
    }

    public function markPartial(): void
    {
        $this->update([
            'status' => 'Terjual Sebagian'
        ]);
    }

    public function markCompleted(): void
    {
        $this->update([
            'status' => 'Selesai'
        ]);
    }
        /*
    |--------------------------------------------------------------------------
    | BUSINESS HELPER
    |--------------------------------------------------------------------------
    */

    public function totalPurchaseWeight(): float
    {
        return (float) $this->items
            ->flatMap(function ($shipmentItem) {
                return $shipmentItem->purchase->items;
            })
            ->sum('purchase_weight');
    }

    public function totalSoldWeight(): float
    {
        return (float) $this->sales
            ->flatMap(function ($sale) {
                return $sale->items;
            })
            ->sum('weight');
    }

    public function progressPercentage(): float
    {
        $purchase = $this->totalPurchaseWeight();

        if ($purchase <= 0) {
            return 0;
        }

        return round(
            ($this->totalSoldWeight() / $purchase) * 100,
            2
        );
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Selesai';
    }
}