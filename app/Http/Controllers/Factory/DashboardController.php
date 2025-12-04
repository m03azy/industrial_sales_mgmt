<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\Factory;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $factory = auth()->user()->factory;
        
        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'No factory profile found. Please contact admin.');
        }

        $stats = [
            'total_products' => Product::where('factory_id', $factory->id)->count(),
            'pending_orders' => SalesOrder::whereHas('orderItems.product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })->whereIn('status', ['draft', 'confirmed'])->count(),
            'delivered_orders' => SalesOrder::whereHas('orderItems.product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })->where('status', 'paid')->count(),
            'monthly_income' => SalesOrder::whereHas('orderItems.product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })->where('status', 'paid')
              ->whereMonth('created_at', now()->month)
              ->sum('total_amount'),
            'low_stock_products' => Product::where('factory_id', $factory->id)
                ->whereRaw('stock_quantity <= low_stock_threshold')
                ->get(),
        ];

        $recent_orders = SalesOrder::with(['customer', 'orderItems.product'])
            ->whereHas('orderItems.product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })
            ->latest()
            ->take(10)
            ->get();

        return view('factory.dashboard', compact('stats', 'recent_orders'));
    }
}
