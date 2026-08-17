<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('shipments')
            ->where('status', 'draft')
            ->update(['status' => 'Draft']);

        DB::table('shipments')
            ->whereIn('status', ['shipping', 'arrived'])
            ->update(['status' => 'Berjalan']);

        DB::table('shipments')
            ->where('status', 'completed')
            ->update(['status' => 'Selesai']);

        DB::statement("
            ALTER TABLE shipments
            MODIFY status ENUM('Draft', 'Berjalan', 'Selesai')
            NOT NULL DEFAULT 'Draft'
        ");
    }

    public function down(): void
    {
        DB::table('shipments')
            ->where('status', 'Draft')
            ->update(['status' => 'draft']);

        DB::table('shipments')
            ->where('status', 'Berjalan')
            ->update(['status' => 'shipping']);

        DB::table('shipments')
            ->where('status', 'Selesai')
            ->update(['status' => 'completed']);

        DB::statement("
            ALTER TABLE shipments
            MODIFY status ENUM('draft', 'shipping', 'arrived', 'completed')
            NOT NULL DEFAULT 'draft'
        ");
    }
};
