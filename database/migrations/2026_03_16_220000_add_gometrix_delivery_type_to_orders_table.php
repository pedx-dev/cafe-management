<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Demo integration migration: extend delivery_type enum to support GoMetrix.
        DB::statement("ALTER TABLE orders MODIFY COLUMN delivery_type ENUM('pickup','delivery','fasttrack','gometrix') NOT NULL DEFAULT 'pickup'");
    }

    public function down(): void
    {
        DB::statement("UPDATE orders SET delivery_type = 'delivery' WHERE delivery_type = 'gometrix'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN delivery_type ENUM('pickup','delivery','fasttrack') NOT NULL DEFAULT 'pickup'");
    }
};
