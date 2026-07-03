<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('type_aliases', function (Blueprint $table) {

            $table->id();

            $table->foreignId('sea_cucumber_type_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('alias');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('type_aliases');
    }
};