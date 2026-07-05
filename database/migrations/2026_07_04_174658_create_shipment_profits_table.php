<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_profits', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Shipment
            |--------------------------------------------------------------------------
            */

            $table->foreignId('shipment_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Cost
            |--------------------------------------------------------------------------
            */

            $table->decimal('purchase_total',18,2)
                ->default(0);

            $table->decimal('expense_total',18,2)
                ->default(0);

            $table->decimal('total_cost',18,2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Revenue
            |--------------------------------------------------------------------------
            */

            $table->decimal('sale_total',18,2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Profit
            |--------------------------------------------------------------------------
            */

            $table->decimal('profit',18,2)
                ->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_profits');
    }
};