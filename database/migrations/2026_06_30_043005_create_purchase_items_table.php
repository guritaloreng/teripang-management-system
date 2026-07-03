<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sea_cucumber_type_id')
                ->constrained();

            // Berat saat beli (belum kering)
            $table->decimal('purchase_weight',10,2);

            // Harga beli per kg
            $table->decimal('price_per_kg',18,2);

            // purchase_weight × price_per_kg
            $table->decimal('subtotal',18,2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};