<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $items = [];
        $total = 0;
        foreach ($cart as $entry) {
            $product = Product::find($entry['product_id']);
            if (!$product) continue;
            $item = [
                'product' => $product,
                'quantity' => $entry['quantity'],
                'unit_price' => $product->selling_price,
            ];
            $item['line_total'] = $item['quantity'] * $item['unit_price'];
            $total += $item['line_total'];
            $items[] = $item;
        }

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        // Ensure requested quantity does not exceed available stock
        $product = Product::find($validated['product_id']);
        if ($product && $validated['quantity'] > $product->stock_quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }
        $cart = session('cart', []);
        $found = false;
        foreach ($cart as &$entry) {
            if ($entry['product_id'] == $validated['product_id']) {
                $entry['quantity'] += $validated['quantity'];
                $found = true;
                break;
            }
        }
        unset($entry);

        if (!$found) {
            $cart[] = ['product_id' => $validated['product_id'], 'quantity' => $validated['quantity']];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Added to cart.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate(['product_id' => 'required|exists:products,id']);
        $cart = session('cart', []);
        $cart = array_values(array_filter($cart, function ($e) use ($validated) {
            return $e['product_id'] != $validated['product_id'];
        }));
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate(['quantities' => 'required|array']);
        $cart = session('cart', []);
        foreach ($cart as &$entry) {
            if (isset($validated['quantities'][$entry['product_id']])) {
                $q = (int) $validated['quantities'][$entry['product_id']];
                $entry['quantity'] = max(1, $q);
            }
        }
        unset($entry);
        session(['cart' => $cart]);
        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty.');
        }

        $linkedCustomer = auth()->check() ? auth()->user()->customer : null;
        if (!$linkedCustomer) {
            return redirect()->route('login')->with('error', 'Please login as a customer to checkout.');
        }

        $validated = $request->validate([
            'order_date' => 'required|date',
            'delivery_method' => 'nullable|string',
        ]);

        // Validate stock for all cart items before creating the order
        $insufficient = [];
        foreach ($cart as $entry) {
            $product = Product::find($entry['product_id']);
            if (!$product) continue;
            if ($entry['quantity'] > $product->stock_quantity) {
                $insufficient[] = $product->name;
            }
        }

        if (!empty($insufficient)) {
            $msg = 'Insufficient stock for: ' . implode(', ', $insufficient) . '. Please adjust quantities.';
            return redirect()->route('cart.index')->with('error', $msg);
        }

        \DB::transaction(function () use ($cart, $linkedCustomer, $validated) {
            $order = SalesOrder::create([
                'customer_id' => $linkedCustomer->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'order_date' => $validated['order_date'],
                'status' => 'draft',
                'sales_agent_id' => auth()->id(),
                'delivery_method' => $validated['delivery_method'] ?? null,
            ]);

            $totalAmount = 0;
            foreach ($cart as $entry) {
                $product = Product::find($entry['product_id']);
                if (!$product) continue;
                $qty = $entry['quantity'];
                $totalPrice = $product->selling_price * $qty;
                OrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'total_price' => $totalPrice,
                ]);
                $totalAmount += $totalPrice;
            }

            $order->update(['total_amount' => $totalAmount]);
            // clear cart
            session()->forget('cart');
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }
}
