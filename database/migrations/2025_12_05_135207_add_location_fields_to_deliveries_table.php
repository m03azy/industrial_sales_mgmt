<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Pickup location coordinates
            $table->decimal('pickup_latitude', 10, 7)->nullable()->after('status');
            $table->decimal('pickup_longitude', 10, 7)->nullable()->after('pickup_latitude');
            $table->string('pickup_address')->nullable()->after('pickup_longitude');
            
            // Delivery location coordinates
            $table->decimal('delivery_latitude', 10, 7)->nullable()->after('pickup_address');
            $table->decimal('delivery_longitude', 10, 7)->nullable()->after('delivery_latitude');
            $table->string('delivery_address')->nullable()->after('delivery_longitude');
            
            // Distance and duration
            $table->decimal('distance_km', 8, 2)->nullable()->after('delivery_address');
            $table->integer('estimated_duration_minutes')->nullable()->after('distance_km');
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_latitude',
                'pickup_longitude',
                'pickup_address',
                'delivery_latitude',
                'delivery_longitude',
                'delivery_address',
                'distance_km',
                'estimated_duration_minutes'
            ]);
        });
    }
};
