<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivingItem extends Model
{
    protected $fillable = [

        'receiving_id',

        'sea_cucumber_type_id',

        'received_weight',

        'remaining_weight'

    ];

    protected $casts = [

        'received_weight' => 'decimal:2',

        'remaining_weight' => 'decimal:2'

    ];

    public function receiving(): BelongsTo
    {
        return $this->belongsTo(Receiving::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(SeaCucumberType::class,'sea_cucumber_type_id');
    }
}