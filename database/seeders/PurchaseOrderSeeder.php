<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $supplier = Supplier::first();
        if (! $supplier) return;

        $products = Product::take(3)->get();
        if ($products->isEmpty()) return;

        $po = PurchaseOrder::firstOrCreate([
            'order_number' => 'PO-SAMPLE-1'
        ], [
            'supplier_id' => $supplier->id,
            'order_date' => now()->toDateString(),
            'status' => 'received',
            'total_amount' => 0,
        ]);

        $total = 0;
        foreach ($products as $p) {
            $qty = 5;
            $unit = $p->cost_price ?? 0;
            $line = $unit * $qty;

            PurchaseOrderItem::firstOrCreate([
                'purchase_order_id' => $po->id,
                'product_id' => $p->id,
            ], [
                'quantity' => $qty,
                'unit_price' => $unit,
                'total_price' => $line,
            ]);

            $total += $line;
        }

        $po->update(['total_amount' => $total]);
    }
}
