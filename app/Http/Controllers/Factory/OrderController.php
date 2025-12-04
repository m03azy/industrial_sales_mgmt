<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $factory = auth()->user()->factory;
        
        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'No factory profile found.');
        }

        $orders = SalesOrder::with(['customer', 'orderItems.product'])
            ->whereHas('orderItems.product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('factory.orders.index', compact('orders'));
    }

    public function show(SalesOrder $order)
    {
        $factory = auth()->user()->factory;
        
        // Check if this order contains products from this factory
        $hasFactoryProducts = $order->orderItems()
            ->whereHas('product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })->exists();

        if (!$hasFactoryProducts) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['customer', 'orderItems.product']);

        return view('factory.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, SalesOrder $order)
    {
        $factory = auth()->user()->factory;
        
        $hasFactoryProducts = $order->orderItems()
            ->whereHas('product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })->exists();

        if (!$hasFactoryProducts) {
            abort(403, 'Unauthorized access to this order.');
        }

        $validated = $request->validate([
            'status' => 'required|in:confirmed,shipped,paid',
        ]);

        $order->update(['status' => $validated['status']]);

        return redirect()->route('factory.orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
