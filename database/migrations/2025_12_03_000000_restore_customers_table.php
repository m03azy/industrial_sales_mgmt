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
        // If customers table is missing but retailers exist (rename happened), recreate customers
        if (!Schema::hasTable('customers') && Schema::hasTable('retailers')) {
            try {
                Schema::create('customers', function (Blueprint $table) {
                    $table->id();
                    $table->string('company_name');
                    $table->string('contact_person');
                    $table->string('email')->unique();
                    $table->string('phone')->nullable();
                    $table->text('address')->nullable();
                    // create user_id column and add FK with an explicit name to avoid duplicate constraint name errors
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {
                // If the table or indexes already exist for the current connection, ignore and continue
            }

            // add foreign key constraint with a custom name if users table exists
            if (Schema::hasTable('users')) {
                try {
                    Schema::table('customers', function (Blueprint $table) {
                        $table->foreign('user_id', 'fk_customers_user')->references('id')->on('users')->nullOnDelete();
                    });
                } catch (\Throwable $e) {
                    // ignore duplicate FK/index errors in test or other environments
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::dropIfExists('customers');
        }
    }
};
