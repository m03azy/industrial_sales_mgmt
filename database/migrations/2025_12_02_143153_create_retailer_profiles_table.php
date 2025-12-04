<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('retailer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id')->constrained('retailers')->onDelete('cascade');
            $table->string('business_type')->nullable();
            $table->enum('preferred_delivery_method', ['pickup', 'delivery', 'both'])->default('both');
            $table->text('delivery_address')->nullable();
            $table->string('delivery_instructions')->nullable();
            $table->json('preferences')->nullable(); // For additional settings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_profiles');
    }
};
