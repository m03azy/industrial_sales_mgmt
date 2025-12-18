<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\Retailer;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmed;
use App\Mail\OrderShipped;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = SalesOrder::with('retailer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('retailer', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%")
                        ->orWhere('contact_person', 'like', "%{$search}%");
                  });
        }

        if (auth()->user()->role === 'retailer') {
            $retailer = Retailer::where('user_id', auth()->id())->first();
            if ($retailer) {
                $query->where('customer_id', $retailer->id); // Assuming customer_id is still used for retailer FK
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $retailers = Retailer::all();
        $products = Product::where('stock_quantity', '>', 0)->get();
        return view('orders.create', compact('retailers', 'products'));
    }

    public function store(Request $request)
    {
        $rules = [
            'order_date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];

        // if user is not a retailer or doesn't have linked retailer, require customer_id (retailer_id)
        $linkedRetailer = auth()->check() ? auth()->user()->retailer : null;
        if (!$linkedRetailer) {
            $rules['customer_id'] = 'required|exists:retailers,id';
        } else {
            $rules['customer_id'] = 'nullable|exists:retailers,id';
        }

        $validated = $request->validate($rules);

    DB::transaction(function () use ($validated, $linkedRetailer) {
            $order = SalesOrder::create([
                'customer_id' => $linkedRetailer ? $linkedRetailer->id : $validated['customer_id'],
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'order_date' => $validated['order_date'],
                'status' => 'draft',
                'sales_agent_id' => auth()->id(),
            ]);

            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::find($item['product_id']);
                $totalPrice = $product->selling_price * $item['quantity'];

                OrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'total_price' => $totalPrice,
                ]);

                $totalAmount += $totalPrice;

                // Deduct stock immediately or wait for confirmation? 
                // Let's deduct on confirmation.
            }

            $order->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show(SalesOrder $order) // Route model binding might need explicit binding if param name differs
    {
        $order->load(['retailer', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(SalesOrder $order)
    {
        $order->load(['retailer', 'orderItems.product']);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, SalesOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,confirmed,shipped,paid,cancelled',
        ]);

        $oldStatus = $order->status;

        // Handle stock deduction on confirmation
        if ($validated['status'] === 'confirmed' && $order->status === 'draft') {
            foreach ($order->orderItems as $item) {
                $item->product->decrement('stock_quantity', $item->quantity);
                $item->product->inventoryTransactions()->create([
                    'type' => 'out',
                    'quantity' => $item->quantity,
                    'reference' => $order->order_number,
                    'notes' => 'Order confirmation',
                ]);
            }
        }

        $order->update($validated);

        // Send email notifications based on status change
        if ($oldStatus !== $validated['status']) {
            $order->load('retailer');
            
            if ($validated['status'] === 'confirmed') {
                Mail::to($order->retailer->email)->send(new OrderConfirmed($order));
            } elseif ($validated['status'] === 'shipped') {
                Mail::to($order->retailer->email)->send(new OrderShipped($order));
            }
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(SalesOrder $order)
    {
        // Optional: Restore stock if cancelled/deleted?
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted.');
    }

    /**
     * Get order details in JSON for AJAX requests.
     */
    public function getDetails(SalesOrder $order)
    {
        return response()->json([
            'delivery_address' => $order->delivery_address,
            'delivery_latitude' => $order->delivery_latitude,
            'delivery_longitude' => $order->delivery_longitude,
            'retailer' => $order->retailer ? $order->retailer->company_name : 'No Retailer',
            'total_amount' => $order->total_amount,
        ]);
    }
}
