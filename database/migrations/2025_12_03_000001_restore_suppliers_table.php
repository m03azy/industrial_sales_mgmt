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
        if (!Schema::hasTable('suppliers') && Schema::hasTable('factories')) {
            try {
                Schema::create('suppliers', function (Blueprint $table) {
                    $table->id();
                    $table->string('company_name');
                    $table->string('contact_person')->nullable();
                    $table->string('email')->nullable();
                    $table->string('phone')->nullable();
                    $table->text('address')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                // ignore if already exists in the current connection
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('suppliers')) {
            Schema::dropIfExists('suppliers');
        }
    }
};
