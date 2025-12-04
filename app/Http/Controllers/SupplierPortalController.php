<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class SupplierPortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->supplier_id) {
            abort(403, 'Access denied');
        }

        $orders = PurchaseOrder::where('supplier_id', $user->supplier_id)->latest()->paginate(15);
        return view('supplier.orders.index', compact('orders'));
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $user = auth()->user();
        if (!$user || $user->supplier_id !== $purchaseOrder->supplier_id) {
            abort(403, 'Access denied');
        }

        $purchaseOrder->load('items.product');
        return view('supplier.orders.show', compact('purchaseOrder'));
    }

    public function markShipped(Request $request, PurchaseOrder $purchaseOrder)
    {
        $user = auth()->user();
        if (!$user || $user->supplier_id !== $purchaseOrder->supplier_id) {
            abort(403, 'Access denied');
        }

        $purchaseOrder->update([
            'status' => 'shipped',
            'received_date' => null,
        ]);

        return redirect()->route('supplier.orders.show', $purchaseOrder)->with('success', 'Marked as shipped.');
    }

    public function markDelivered(Request $request, PurchaseOrder $purchaseOrder)
    {
        $user = auth()->user();
        if (!$user || $user->supplier_id !== $purchaseOrder->supplier_id) {
            abort(403, 'Access denied');
        }

        // When supplier marks delivered, mark as received and update inventory
        if ($purchaseOrder->status === 'received') {
            return redirect()->back()->with('info', 'Purchase order already marked received.');
        }

        \DB::transaction(function () use ($purchaseOrder) {
            foreach ($purchaseOrder->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock_quantity', $item->quantity);
                    $product->inventoryTransactions()->create([
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'reference' => $purchaseOrder->order_number,
                        'notes' => 'Received via supplier delivery',
                    ]);
                }
            }

            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => now()->toDateString(),
            ]);
        });

        return redirect()->route('supplier.orders.show', $purchaseOrder)->with('success', 'Marked as delivered and inventory updated.');
    }
}
