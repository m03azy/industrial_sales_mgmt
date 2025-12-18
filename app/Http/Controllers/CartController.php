<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\SalesOrder;
use App\Models\OrderItem;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('product.factory')
            ->where('user_id', auth()->id())
            ->get();
        
        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.18; // 18% tax
        $total = $subtotal + $tax;

        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check stock availability
        if ($validated['quantity'] > $product->stock_quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        // Check if item already in cart
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $validated['quantity'];
            
            if ($newQuantity > $product->stock_quantity) {
                return redirect()->back()->with('error', 'Cannot add more. Stock limit reached.');
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $product->selling_price,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $validated['cart_item_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Check stock
        if ($validated['quantity'] > $cartItem->product->stock_quantity) {
            return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id'
        ]);

        CartItem::where('id', $validated['cart_item_id'])
            ->where('user_id', auth()->id())
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        CartItem::where('user_id', auth()->id())->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    public function checkout()
    {
        $cartItems = CartItem::with('product.factory')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check if user is a retailer
        $retailer = auth()->user()->retailer;
        if (!$retailer) {
            return redirect()->route('cart.index')->with('error', 'Only retailers can checkout.');
        }

        $subtotal = $cartItems->sum('subtotal');
        $tax = $subtotal * 0.18;
        $total = $subtotal + $tax;

        return view('cart.checkout', compact('cartItems', 'retailer', 'subtotal', 'tax', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'delivery_address' => 'required|string|max:500',
            'delivery_notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money',
        ]);

        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $retailer = auth()->user()->retailer;
        if (!$retailer) {
            return redirect()->route('cart.index')->with('error', 'Only retailers can place orders.');
        }

        // Validate stock for all items
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Insufficient stock for {$item->product->name}. Please update your cart.");
            }
        }

        \DB::transaction(function () use ($cartItems, $retailer, $validated, $request) {
            // Calculate totals
            $subtotal = $cartItems->sum('subtotal');
            $tax = $subtotal * 0.18;
            $total = $subtotal + $tax;

            // Create order
            $order = SalesOrder::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
                'customer_id' => $retailer->id,
                'retailer_id' => $retailer->id,
                'user_id' => auth()->id(),
                'order_date' => now(),
                'status' => 'draft',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $total,
                'delivery_address' => $validated['delivery_address'],
                'delivery_latitude' => $request->delivery_latitude,
                'delivery_longitude' => $request->delivery_longitude,
                'delivery_notes' => $validated['delivery_notes'] ?? null,
                'payment_method' => $validated['payment_method'],
            ]);

            // Create order items and reduce stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'total_price' => $cartItem->subtotal,
                ]);

                // Reduce stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Clear cart
            CartItem::where('user_id', auth()->id())->delete();
        });

        return redirect()->route('retailer.orders.index')
            ->with('success', 'Order placed successfully! You will receive a confirmation email shortly.');
    }
}
