<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_ledgers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('investor_id')->constrained()->cascadeOnDelete();

            $table->date('transaction_date');

            $table->enum('transaction_type', [
                'deposit',
                'withdraw'
            ]);

            $table->decimal('amount',18,2);

            $table->text('note')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_ledgers');
    }
};