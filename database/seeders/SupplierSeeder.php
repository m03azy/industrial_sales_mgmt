<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $suppliers = [
            ['company_name' => 'Acme Supplies', 'contact_person' => 'Jane Doe', 'email' => 'jane@acme.example', 'phone' => '555-0100', 'address' => '123 Industrial Way'],
            ['company_name' => 'Global Parts Co', 'contact_person' => 'John Smith', 'email' => 'john@global.example', 'phone' => '555-0123', 'address' => '456 Warehouse Rd'],
        ];

        foreach ($suppliers as $s) {
            $supplier = Supplier::firstOrCreate(['company_name' => $s['company_name']], $s);

            // Create a supplier user account if it doesn't exist
            $supplierRole = \App\Models\Role::where('name', 'supplier')->first();
            if ($supplierRole) {
                $email = $s['email'];
                $user = \App\Models\User::where('email', $email)->first();
                if (!$user) {
                    \App\Models\User::create([
                        'name' => $s['company_name'] . ' Account',
                        'email' => $email,
                        'password' => bcrypt('password'),
                        'role_id' => $supplierRole->id,
                        'supplier_id' => $supplier->id,
                    ]);
                } else {
                    // ensure user is linked to supplier
                    if (!$user->supplier_id) {
                        $user->update(['supplier_id' => $supplier->id, 'role_id' => $supplierRole->id]);
                    }
                }
            }
        }
    }
}
