<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesOrder;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Retailer;
use App\Models\User;
use Illuminate\Support\Str;

class SalesOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salesAgent = User::whereHas('role', function ($q) {
            $q->where('name', 'sales_agent');
        })->first();

        $customers = Retailer::all();
        $products = Product::all();

        if ($salesAgent && $customers->count() && $products->count()) {
            foreach ($customers->take(3) as $customer) {
                $order = SalesOrder::create([
                    'customer_id' => $customer->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(6)),
                    'order_date' => now()->toDateString(),
                    'status' => 'draft',
                    'sales_agent_id' => $salesAgent->id,
                    'total_amount' => 0,
                ]);

                $total = 0;
                // add 1-2 items
                $sampleProducts = $products->shuffle()->take(2);
                foreach ($sampleProducts as $p) {
                    $qty = rand(1, 5);
                    $unit = $p->selling_price;
                    $lineTotal = $unit * $qty;
                    OrderItem::create([
                        'sales_order_id' => $order->id,
                        'product_id' => $p->id,
                        'quantity' => $qty,
                        'unit_price' => $unit,
                        'total_price' => $lineTotal,
                    ]);
                    $total += $lineTotal;
                }

                $order->update(['total_amount' => $total]);
            }
        }
    }
}
