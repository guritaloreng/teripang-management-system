<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [

        'sale_id',

        'sea_cucumber_type_id',

        'weight',

        'price',

        'subtotal',

        'status',

    ];

    protected $casts = [

        'weight' => 'decimal:2',

        'price' => 'decimal:2',

        'subtotal' => 'decimal:2',

    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            SeaCucumberType::class,
            'sea_cucumber_type_id'
        );
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Selesai';
    }

    public function isPartial(): bool
    {
        return $this->status === 'Terjual Sebagian';
    }
}