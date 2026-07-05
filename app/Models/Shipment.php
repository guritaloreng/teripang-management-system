<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    public const STATUS_DRAFT = 'Draft';

    protected $fillable = [

        'shipment_number',

        'shipment_date',

        'destination',

        'status',

        'note',

    ];

    protected $casts = [

        'shipment_date' => 'date',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Jenis teripang yang ada di Shipment
    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItem::class);
    }

    // Daftar Purchase yang tergabung dalam Shipment
    public function purchases(): HasMany
    {
        return $this->hasMany(ShipmentPurchase::class);
    }

    // Semua nota penjualan dari Shipment ini
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Selesai';
    }
}