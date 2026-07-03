<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            $table->string('purchase_number')->unique();

            $table->string('supplier_invoice')->nullable();

            $table->date('purchase_date');

            $table->foreignId('supplier_id')->constrained();

            $table->decimal('grand_total',18,2)->default(0);

            $table->string('photo')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};