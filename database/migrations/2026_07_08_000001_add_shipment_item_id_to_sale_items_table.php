<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sale_items', 'shipment_item_id')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->foreignId('shipment_item_id')
                    ->nullable()
                    ->after('sea_cucumber_type_id')
                    ->constrained()
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sale_items', 'shipment_item_id')) {
            Schema::table('sale_items', function (Blueprint $table) {
                $table->dropConstrainedForeignId('shipment_item_id');
            });
        }
    }
};
