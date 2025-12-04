<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['sku' => 'P-1001', 'name' => 'Industrial Bolt', 'description' => 'High strength bolt', 'cost_price' => 0.50, 'selling_price' => 1.25, 'stock_quantity' => 500, 'low_stock_threshold' => 50, 'category' => 'Hardware'],
            ['sku' => 'P-1002', 'name' => 'Hydraulic Pump', 'description' => '12V hydraulic pump', 'cost_price' => 75.00, 'selling_price' => 150.00, 'stock_quantity' => 12, 'low_stock_threshold' => 5, 'category' => 'Machinery'],
            ['sku' => 'P-1003', 'name' => 'Conveyor Belt', 'description' => 'Reinforced conveyor belt', 'cost_price' => 120.00, 'selling_price' => 250.00, 'stock_quantity' => 8, 'low_stock_threshold' => 3, 'category' => 'Material Handling'],
            ['sku' => 'P-1004', 'name' => 'Bearing Set', 'description' => 'Ball bearing set', 'cost_price' => 5.00, 'selling_price' => 12.00, 'stock_quantity' => 120, 'low_stock_threshold' => 20, 'category' => 'Hardware'],
            ['sku' => 'P-1005', 'name' => 'Control Relay', 'description' => '24V relay', 'cost_price' => 8.00, 'selling_price' => 19.50, 'stock_quantity' => 40, 'low_stock_threshold' => 10, 'category' => 'Electronics'],
            ['sku' => 'P-1006', 'name' => 'Pressure Gauge', 'description' => '0-300 PSI gauge', 'cost_price' => 15.00, 'selling_price' => 35.00, 'stock_quantity' => 25, 'low_stock_threshold' => 5, 'category' => 'Instrumentation'],
            ['sku' => 'P-1007', 'name' => 'Safety Valve', 'description' => 'Relief valve', 'cost_price' => 22.00, 'selling_price' => 48.00, 'stock_quantity' => 18, 'low_stock_threshold' => 5, 'category' => 'Piping'],
            ['sku' => 'P-1008', 'name' => 'Lubricant 5L', 'description' => 'Industrial lubricant', 'cost_price' => 10.00, 'selling_price' => 22.00, 'stock_quantity' => 60, 'low_stock_threshold' => 15, 'category' => 'Consumables'],
        ];

        foreach ($items as $item) {
            Product::updateOrCreate(['sku' => $item['sku']], $item);
        }
    }
}
