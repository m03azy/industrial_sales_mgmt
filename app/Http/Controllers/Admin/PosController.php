<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        // Get initial products for the grid
        $products = Product::where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->limit(20)
            ->get();

        // Get customers for the dropdown
        $customers = User::where('role', 'retailer')->orderBy('name')->get();

        return view('admin.pos.index', compact('products', 'customers'));
    }

    /**
     * Search for products via AJAX.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $products = Product::where('stock_quantity', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get();

        return response()->json($products);
    }

    /**
     * Process the POS checkout.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'amount_paid' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $itemsToCreate = [];

            foreach ($validated['items'] as $itemData) {
                $product = Product::lockForUpdate()->find($itemData['id']);

                if ($product->stock_quantity < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $lineTotal = $product->price * $itemData['quantity'];
                $subtotal += $lineTotal;

                $itemsToCreate[] = [
                    'product' => $product,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price,
                    'total' => $lineTotal,
                ];

                // Decrement stock
                $product->decrement('stock_quantity', $itemData['quantity']);
            }

            $tax = $subtotal * 0.18; // 18% Tax
            $total = $subtotal + $tax;

            // Create Order
            $order = SalesOrder::create([
                'order_number' => 'POS-' . strtoupper(Str::random(8)),
                'user_id' => $validated['customer_id'],
                'retailer_id' => User::find($validated['customer_id'])->retailer->id ?? null, // Try to link to retailer profile if exists
                'status' => 'delivered', // POS orders are immediate
                'total_amount' => $total,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'delivery_address' => 'Over the Counter',
                'invoice_number' => 'INV-' . date('Y') . '-' . strtoupper(Str::random(6)),
            ]);

            // Create Order Items
            foreach ($itemsToCreate as $item) {
                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['total'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order completed successfully!',
                'order_id' => $order->id,
                'invoice_url' => route('invoice.view', $order->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
