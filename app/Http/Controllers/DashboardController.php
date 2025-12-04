<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user) {
            if ($user->hasRole('factory')) {
                return redirect()->route('dashboard.factory');
            }
            if ($user->hasRole('retailer')) {
                return redirect()->route('dashboard.retailer');
            }
            if ($user->hasRole('driver')) {
                return redirect()->route('dashboard.driver');
            }
        }

        $stats = [
            'total_products' => Product::count(),
            'total_customers' => \App\Models\Retailer::count(),
            'total_orders' => SalesOrder::count(),
            'pending_orders' => SalesOrder::where('status', 'draft')->count(),
            'low_stock_products' => Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->get(),
            'recent_orders' => SalesOrder::with('retailer')->latest()->take(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }

    public function factory()
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('factory')) {
            abort(403);
        }

        // For factories, we show orders that contain their products
        // This is a bit complex since orders are retailer-centric. 
        // Ideally, we'd filter order items by factory_id.
        // For now, let's show products and recent sales of their products.
        
        $factory = $user->factory;
        $products = $factory ? $factory->products()->latest()->take(5)->get() : collect();
        
        // Calculate stats
        $totalProducts = $factory ? $factory->products()->count() : 0;
        $lowStock = $factory ? $factory->products()->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count() : 0;
        
        // Revenue (approximate based on sold items)
        $revenue = 0;
        if ($factory) {
            $revenue = \App\Models\OrderItem::whereHas('product', function($q) use ($factory) {
                $q->where('factory_id', $factory->id);
            })->sum('total_price');
        }

        $stats = [
            'total_products' => $totalProducts,
            'low_stock' => $lowStock,
            'revenue' => $revenue,
        ];

        return view('dashboards.factory', compact('products', 'stats'));
    }

    public function retailer()
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('retailer')) {
            abort(403);
        }

        $retailer = $user->retailer;
        $orders = $retailer ? $retailer->salesOrders()->latest()->take(10)->get() : collect();

        $stats = [
            'orders_count' => $retailer ? $retailer->salesOrders()->count() : 0,
            'pending' => $retailer ? $retailer->salesOrders()->where('status', 'draft')->count() : 0,
            'total_spent' => $retailer ? $retailer->salesOrders()->sum('total_amount') : 0,
        ];

        return view('dashboards.retailer', compact('retailer', 'orders', 'stats'));
    }

    public function driver()
    {
        $user = auth()->user();
        
        // Get or create driver profile
        $driver = $user->driver;
        
        if (!$driver) {
            // If no driver profile exists, show empty dashboard
            $activeDeliveries = collect();
            $stats = [
                'assigned' => 0,
                'completed' => 0,
                'earnings' => 0,
            ];
            return view('dashboards.driver', compact('activeDeliveries', 'stats'));
        }

        $activeDeliveries = $driver->deliveries()
            ->whereIn('status', ['assigned', 'in_transit'])
            ->with('order.retailer')
            ->get();

        $stats = [
            'assigned' => $activeDeliveries->count(),
            'completed' => $driver->deliveries()
                ->where('status', 'delivered')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'earnings' => 0, // Placeholder for now
        ];

        return view('dashboards.driver', compact('activeDeliveries', 'stats'));
    }
}
