<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {

            $table->id();

            $table->string('shipment_number')->unique();

            $table->date('shipment_date');

            $table->string('destination')->default('Makassar');

            $table->enum('status',[
                'Draft',
                'Berjalan',
                'Selesai'
            ])->default('Draft');

            $table->decimal('shipping_cost',18,2)->default(0);

            $table->text('note')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
