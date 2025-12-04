<?php

namespace App\Http\Controllers\Factory;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $factory = $user->factory;

        if (!$factory) {
            return redirect()->route('dashboard')->with('error', 'Factory profile not found.');
        }

        // Monthly Revenue (Last 12 months)
        $monthlyRevenue = OrderItem::whereHas('product', function($q) use ($factory) {
            $q->where('factory_id', $factory->id);
        })
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as revenue')
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Top Selling Products (Last 6 months)
        $topProducts = OrderItem::whereHas('product', function($q) use ($factory) {
            $q->where('factory_id', $factory->id);
        })
        ->with('product')
        ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(total_price) as total_revenue')
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('product_id')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();

        // Order Trends (Orders containing factory products)
        $orderTrends = OrderItem::whereHas('product', function($q) use ($factory) {
            $q->where('factory_id', $factory->id);
        })
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(DISTINCT sales_order_id) as order_count')
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Low Stock Products
        $lowStockProducts = $factory->products()
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->limit(10)
            ->get();

        // Top Retailers (by purchase volume)
        $topRetailers = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('sales_orders', 'order_items.sales_order_id', '=', 'sales_orders.id')
            ->join('retailers', 'sales_orders.customer_id', '=', 'retailers.id')
            ->where('products.factory_id', $factory->id)
            ->selectRaw('retailers.company_name, SUM(order_items.total_price) as total_spent, COUNT(DISTINCT sales_orders.id) as order_count')
            ->groupBy('retailers.id', 'retailers.company_name')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        return view('factory.analytics.index', compact(
            'monthlyRevenue',
            'topProducts',
            'orderTrends',
            'lowStockProducts',
            'topRetailers'
        ));
    }
}
