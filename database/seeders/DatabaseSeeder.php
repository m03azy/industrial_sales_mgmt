<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call(RoleSeeder::class);

        // Create a super admin user
        $adminRole = \App\Models\Role::where('name', 'super_admin')->first();

        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
        ]);

        // Create a sales agent user
        $salesRole = \App\Models\Role::where('name', 'sales_agent')->first();

        \App\Models\User::create([
            'name' => 'Sales Agent',
            'email' => 'sales@example.com',
            'password' => bcrypt('password'),
            'role_id' => $salesRole->id,
        ]);

            // Seed products, customers, suppliers and sample orders
            $this->call([
                \Database\Seeders\ProductSeeder::class,
                \Database\Seeders\CustomerSeeder::class,
                \Database\Seeders\SupplierSeeder::class,
                \Database\Seeders\SalesOrderSeeder::class,
            ]);
        
    }
}
