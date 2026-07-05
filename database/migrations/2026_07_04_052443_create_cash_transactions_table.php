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
        Schema::create('cash_transactions', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            $table->date('transaction_date');

            $table->enum('transaction_type', [

                'Modal Investor',

                'Pembelian',

                'Penjualan',

                'Operasional',

                'Penarikan Modal',

                'Penyesuaian'

            ]);

            /*
            |--------------------------------------------------------------------------
            | Reference
            |--------------------------------------------------------------------------
            */

            $table->string('reference_type')
                ->nullable();

            $table->unsignedBigInteger('reference_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            $table->string('description');

            $table->text('note')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Money
            |--------------------------------------------------------------------------
            */

            $table->decimal('cash_in', 18, 2)
                ->default(0);

            $table->decimal('cash_out', 18, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | Audit
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
        Schema::dropIfExists('cash_transactions');
    }
};