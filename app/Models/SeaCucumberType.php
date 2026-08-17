<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeaCucumberType extends Model
{
    protected $fillable = [
        'name',
        'stock_overview_status',
    ];

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
