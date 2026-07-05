<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentItem extends Model
{
    protected $fillable = [

        'shipment_id',

        'sea_cucumber_type_id',

        'weight',

        'status',

    ];

    protected $casts = [

        'weight' => 'decimal:2',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            SeaCucumberType::class,
            'sea_cucumber_type_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function isNotSold(): bool
    {
        return $this->status === 'Belum Dijual';
    }

    public function isPartial(): bool
    {
        return $this->status === 'Terjual Sebagian';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Selesai';
    }
}