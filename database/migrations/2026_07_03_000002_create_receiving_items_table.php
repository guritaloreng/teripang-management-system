<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receiving_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('receiving_id')->constrained()->cascadeOnDelete();

            $table->foreignId('sea_cucumber_type_id')->constrained();

            $table->decimal('received_weight',10,2);

            $table->decimal('remaining_weight',10,2);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receiving_items');
    }
};