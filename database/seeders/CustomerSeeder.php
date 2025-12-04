<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            ['company_name' => 'Acme Manufacturing', 'contact_person' => 'John Doe', 'email' => 'acme@example.com', 'phone' => '555-0100', 'address' => '123 Industrial Way'],
            ['company_name' => 'Beta Logistics', 'contact_person' => 'Jane Smith', 'email' => 'beta@example.com', 'phone' => '555-0110', 'address' => '45 Shipping Lane'],
            ['company_name' => 'Gamma Fabrication', 'contact_person' => 'Paul Jones', 'email' => 'gamma@example.com', 'phone' => '555-0120', 'address' => '88 Metal Drive'],
            ['company_name' => 'Delta Supplies', 'contact_person' => 'Sara Lee', 'email' => 'delta@example.com', 'phone' => '555-0130', 'address' => '7 Supply Road'],
            ['company_name' => 'Epsilon Parts', 'contact_person' => 'Mark White', 'email' => 'epsilon@example.com', 'phone' => '555-0140', 'address' => '42 Parts Ave'],
        ];

        foreach ($customers as $c) {
            Customer::updateOrCreate(['email' => $c['email']], $c);
        }
    }
}
