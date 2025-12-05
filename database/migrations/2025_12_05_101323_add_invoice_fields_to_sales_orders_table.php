<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('sales_orders', 'delivery_address')) {
                $table->string('delivery_address')->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'delivery_notes')) {
                $table->text('delivery_notes')->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('sales_orders', 'retailer_id')) {
                $table->foreignId('retailer_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('sales_orders', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales_orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_address', 'delivery_notes', 'payment_method', 'retailer_id', 'user_id']);
        });
    }
};
