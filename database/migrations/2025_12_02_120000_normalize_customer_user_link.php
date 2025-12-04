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
        // This migration is a safe helper to backfill any legacy users.customer_id
        // values into the canonical customers.user_id column, then drop the
        // users.customer_id column if present.

        if (!Schema::hasTable('users')) {
            return;
        }

        if (!Schema::hasTable('customers')) {
            return;
        }

        // Backfill in a transaction for safety
        \DB::transaction(function () {
            // Only proceed if users.customer_id column exists
            if (Schema::hasColumn('users', 'customer_id')) {
                $rows = \DB::table('users')->whereNotNull('customer_id')->get(['id', 'customer_id']);
                foreach ($rows as $r) {
                    // If a customers row with that id exists, attempt to set user_id
                    $customer = \DB::table('customers')->where('id', $r->customer_id)->first();
                    if ($customer) {
                        // If user_id is empty or mismatched, update it to point to the user
                        if (empty($customer->user_id) || $customer->user_id != $r->id) {
                            \DB::table('customers')->where('id', $r->customer_id)->update(['user_id' => $r->id]);
                        }
                    } else {
                        // Optionally create a customer record for the user; here we skip
                    }
                }

                // Now drop the column (if you prefer not to drop automatically, comment this out)
                if (Schema::hasColumn('users', 'customer_id')) {
                    Schema::table('users', function (Blueprint $table) {
                        // Drop FK if present then drop column
                        try {
                            $table->dropForeign(['customer_id']);
                        } catch (\Exception $e) {
                            // ignore if no FK
                        }
                        $table->dropColumn('customer_id');
                    });
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't restore dropped customer_id automatically.
    }
};
