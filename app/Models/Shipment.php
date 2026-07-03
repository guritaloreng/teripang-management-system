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

    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function receiving(): HasMany
    {
        return $this->hasMany(Receiving::class);
    }

    /**
     * Total berat pembelian berdasarkan jenis
     */
    public function purchaseSummary()
    {
        return PurchaseItem::selectRaw('
                sea_cucumber_type_id,
                SUM(weight) as total_weight
            ')
            ->join(
                'shipment_items',
                'shipment_items.purchase_id',
                '=',
                'purchase_items.purchase_id'
            )
            ->where('shipment_items.shipment_id', $this->id)
            ->groupBy('sea_cucumber_type_id')
            ->with('type')
            ->get();
    }

    /**
     * Total KG sudah terjual
     */
    public function totalSoldKg()
    {
        return SaleItem::join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.shipment_id', $this->id)
            ->sum('sale_items.weight');
    }

    /**
     * Total Invoice
     */
    public function totalInvoice()
    {
        return $this->sales()->count();
    }
}