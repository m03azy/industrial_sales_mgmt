<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE sales_orders MODIFY COLUMN status ENUM('draft', 'confirmed', 'shipped', 'paid', 'cancelled', 'delivered', 'pending') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting might be dangerous if data exists with new statuses, but for completeness:
        // We will just leave it as is or revert to original list if we were strict. 
        // For safety in dev, let's keep the expanded list or just comment it out.
        // But strictly speaking:
        // DB::statement("ALTER TABLE sales_orders MODIFY COLUMN status ENUM('draft', 'confirmed', 'shipped', 'paid', 'cancelled') DEFAULT 'draft'");
    }
};
