<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sale_items', 'status')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->enum('status', [
                    'Terjual Sebagian',
                    'Selesai',
                ])
                    ->default('Terjual Sebagian')
                    ->after('subtotal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sale_items', 'status')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
