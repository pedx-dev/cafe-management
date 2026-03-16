<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN delivery_type ENUM('pickup','delivery','fasttrack') NOT NULL DEFAULT 'pickup'");

        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_provider')->nullable()->after('delivery_type');
            $table->string('courier_reference')->nullable()->after('courier_provider');
            $table->string('courier_status')->nullable()->after('courier_reference');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_provider', 'courier_reference', 'courier_status']);
        });

        DB::statement("UPDATE orders SET delivery_type = 'delivery' WHERE delivery_type = 'fasttrack'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN delivery_type ENUM('pickup','delivery') NOT NULL DEFAULT 'pickup'");
    }
};
