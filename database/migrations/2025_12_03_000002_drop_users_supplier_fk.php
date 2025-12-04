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
        // Some environments had the suppliers table renamed; drop the strict FK on users.supplier_id
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'supplier_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->dropForeign(['supplier_id']);
                });
            } catch (\Throwable $e) {
                // ignore if FK not present or platform doesn't support
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'supplier_id')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
                });
            } catch (\Throwable $e) {
                // ignore if cannot add
            }
        }
    }
};
