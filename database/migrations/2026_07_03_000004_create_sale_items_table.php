<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('sale_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sea_cucumber_type_id')
                ->constrained();

            $table->foreignId('shipment_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            $table->decimal('weight', 10, 2);

            $table->decimal('price', 18, 2);

            $table->decimal('subtotal', 18, 2);

            /*
            |--------------------------------------------------------------------------
            | Business Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'Terjual Sebagian',

                'Selesai',

            ])->default('Terjual Sebagian');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
