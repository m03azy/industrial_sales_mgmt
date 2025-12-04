<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $retailer = auth()->user()->retailer;
        
        if (!$retailer) {
            return redirect()->route('dashboard')->with('error', 'No retailer profile found. Please contact admin.');
        }

        $stats = [
            'total_orders' => SalesOrder::where('customer_id', $retailer->id)->count(),
            'pending_orders' => SalesOrder::where('customer_id', $retailer->id)
                ->whereIn('status', ['draft', 'confirmed'])->count(),
            'completed_orders' => SalesOrder::where('customer_id', $retailer->id)
                ->where('status', 'paid')->count(),
            'total_spent' => SalesOrder::where('customer_id', $retailer->id)
                ->where('status', 'paid')->sum('total_amount'),
        ];

        $recent_orders = SalesOrder::with(['orderItems.product'])
            ->where('customer_id', $retailer->id)
            ->latest()
            ->take(5)
            ->get();

        $featured_products = Product::whereNotNull('factory_id')
            ->where('stock_quantity', '>', 0)
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('retailer.dashboard', compact('stats', 'recent_orders', 'featured_products'));
    }
}
