<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'super_admin', 'label' => 'Super Admin'],
            ['name' => 'sales_manager', 'label' => 'Sales Manager'],
            ['name' => 'sales_agent', 'label' => 'Sales Agent'],
            ['name' => 'warehouse_manager', 'label' => 'Warehouse Manager'],
            ['name' => 'customer', 'label' => 'Customer'],
            ['name' => 'supplier', 'label' => 'Supplier'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::firstOrCreate(
                ['name' => $role['name']],
                ['label' => $role['label']]
            );
        }
    }
}
