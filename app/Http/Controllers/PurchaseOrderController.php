<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with('supplier')->latest()->paginate(10);
        return view('purchase_orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function() use ($validated) {
            $po = PurchaseOrder::create([
                'supplier_id' => $validated['supplier_id'],
                'order_number' => 'PO-' . strtoupper(uniqid()),
                'order_date' => $validated['order_date'],
                'status' => 'ordered',
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $unit = $product->cost_price ?? 0;
                $lineTotal = $unit * $item['quantity'];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $unit,
                    'total_price' => $lineTotal,
                ]);

                $total += $lineTotal;
            }

            $po->update(['total_amount' => $total]);
        });

        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order created.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'items.product']);
        return view('purchase_orders.show', ['order' => $purchaseOrder]);
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items');
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $purchaseOrder->update($validated);

        return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order updated.');
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase-orders.index')->with('success', 'Purchase order deleted.');
    }

    /**
     * Mark a purchase order as received and increment product stock.
     */
    public function receive(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return redirect()->back()->with('info', 'Purchase order already received.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $totalReceived = 0;
            foreach ($purchaseOrder->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                    // record inventory transaction
                    $product->inventoryTransactions()->create([
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'reference' => $purchaseOrder->order_number,
                        'notes' => 'Received via Purchase Order',
                    ]);
                }
                $totalReceived += $item->quantity;
            }

            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => now()->toDateString(),
            ]);
        });

        return redirect()->route('purchase-orders.show', $purchaseOrder)->with('success', 'Purchase order marked as received and inventory updated.');
    }
}
