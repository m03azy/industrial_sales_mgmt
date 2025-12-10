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
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE sales_orders MODIFY COLUMN status ENUM('draft', 'confirmed', 'shipped', 'paid', 'cancelled', 'delivered', 'pending') DEFAULT 'draft'");
        } elseif ($driver === 'pgsql') {
            // For PostgreSQL, we just need to ensure the check constraint doesn't blocking us.
            // Since we can't easily modify the type without DBAL or complex SQL, 
            // and usually 'enum' in Laravel/Postgres is just a varchar with a check constraint.
            // We tring to drop the constraint to allow any string is the safest unblocking move.
            // The constraint name is usually table_column_check.
            DB::statement("ALTER TABLE sales_orders DROP CONSTRAINT IF EXISTS sales_orders_status_check");
        } 
        // For SQLite, we do nothing. The original migration has been updated for fresh installs.
        // For existing SQLite dbs, altering is too complex for this hotfix.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for safety
    }
};
