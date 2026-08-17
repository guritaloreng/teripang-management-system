<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('sea_cucumber_types', 'stock_overview_status')) {
            Schema::table('sea_cucumber_types', function (Blueprint $table) {
                $table->string('stock_overview_status')
                    ->default('Active')
                    ->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('sea_cucumber_types', 'stock_overview_status')) {
            Schema::table('sea_cucumber_types', function (Blueprint $table) {
                $table->dropColumn('stock_overview_status');
            });
        }
    }
};
