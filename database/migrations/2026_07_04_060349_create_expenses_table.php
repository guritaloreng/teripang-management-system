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
        Schema::create('expenses', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Expense Information
            |--------------------------------------------------------------------------
            */

            $table->date('expense_date');

            $table->foreignId('shipment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('expense_name');

            $table->decimal('amount', 18, 2);

            $table->string('description');

            $table->text('note')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

        });
    }
        /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};