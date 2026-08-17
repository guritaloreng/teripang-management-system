<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipment_items', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relation
            |--------------------------------------------------------------------------
            */

            $table->foreignId('shipment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sea_cucumber_type_id')
                ->constrained();

            /*
            |--------------------------------------------------------------------------
            | Shipment Data
            |--------------------------------------------------------------------------
            */

            $table->decimal('weight',12,2);

            $table->enum('status',[
                'Belum Dijual',
                'Terjual Sebagian',
                'Selesai'
            ])->default('Belum Dijual');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_items');
    }
};
