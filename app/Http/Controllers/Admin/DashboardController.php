<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Factory;
use App\Models\Retailer;
use App\Models\SalesOrder;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'pending_approvals' => User::where('status', 'pending')->count(),
            'total_factories' => Factory::count(),
            'total_retailers' => Retailer::count(),
            'total_products' => Product::count(),
            'total_orders' => SalesOrder::count(),
            'monthly_revenue' => SalesOrder::where('status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
        ];

        $pending_users = User::where('status', 'pending')
            ->with(['factory', 'retailer'])
            ->latest()
            ->take(10)
            ->get();

        $recent_orders = SalesOrder::with(['customer'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pending_users', 'recent_orders'));
    }
}
