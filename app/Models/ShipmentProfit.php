<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentProfit extends Model
{
    protected $fillable = [

        'shipment_id',

        'purchase_total',

        'expense_total',

        'total_cost',

        'sale_total',

        'profit',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}