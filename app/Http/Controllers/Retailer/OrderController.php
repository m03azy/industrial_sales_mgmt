<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $retailer = auth()->user()->retailer;
        
        if (!$retailer) {
            return redirect()->route('dashboard')->with('error', 'No retailer profile found.');
        }

        $orders = SalesOrder::with(['orderItems.product'])
            ->where('customer_id', $retailer->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('retailer.orders.index', compact('orders'));
    }

    public function show(SalesOrder $order)
    {
        $retailer = auth()->user()->retailer;
        
        if ($order->customer_id !== $retailer->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['orderItems.product.factory']);

        return view('retailer.orders.show', compact('order'));
    }
}
