<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(auth()->user()->status === 'pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Account Pending Approval</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Your account is currently under review by an administrator. You will be notified once your account is approved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm">Total Products</div>
                            <div class="text-3xl font-bold text-gray-900">{{ $stats['total_products'] }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm">Total Customers</div>
                            <div class="text-3xl font-bold text-gray-900">{{ $stats['total_customers'] }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm">Total Orders</div>
                            <div class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm">Pending Orders</div>
                            <div class="text-3xl font-bold text-orange-600">{{ $stats['pending_orders'] }}</div>
                        </div>
                    </div>

                    <!-- Export / Invoicing Card -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-gray-500 text-sm">Utilities</div>
                            <div class="mt-3">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('export.data', 'products') }}" class="inline-block text-sm bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded">Export Products (CSV)</a>
                                    <a href="{{ route('export.data', 'customers') }}" class="inline-block text-sm bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded">Export Customers (CSV)</a>
                                    <a href="{{ route('export.data', 'orders') }}" class="inline-block text-sm bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded">Export Orders (CSV)</a>
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.orders.index') }}" class="inline-block text-sm bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded">Manage Orders / Invoicing</a>
                                    @elseif(auth()->user()->role === 'retailer')
                                        <a href="{{ route('retailer.orders.index') }}" class="inline-block text-sm bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded">Manage Orders / Invoicing</a>
                                    @elseif(auth()->user()->role === 'factory')
                                        <a href="{{ route('factory.orders.index') }}" class="inline-block text-sm bg-indigo-600 text-white hover:bg-indigo-700 px-3 py-2 rounded">Manage Orders / Invoicing</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Low Stock Alerts -->
                @if($stats['low_stock_products']->count() > 0)
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Low Stock Alert</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>{{ $stats['low_stock_products']->count() }} product(s) are running low on stock:</p>
                                    <ul class="list-disc list-inside mt-1">
                                        @foreach($stats['low_stock_products'] as $product)
                                            <li>{{ $product->name }} ({{ $product->stock_quantity }} remaining)</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order #</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($stats['recent_orders'] as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                @if(auth()->user()->role === 'admin')
                                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                @elseif(auth()->user()->role === 'retailer')
                                                    <a href="{{ route('retailer.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                @elseif(auth()->user()->role === 'factory')
                                                    <a href="{{ route('factory.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">
                                                @else
                                                    <a href="#" class="text-gray-900">
                                                @endif
                                                    {{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ optional($order->customer)->company_name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->order_date }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                ${{ number_format($order->total_amount, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($order->status === 'paid') bg-green-100 text-green-800
                                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('products.create') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Add Product</h3>
                        </div>
                    </a>

                    <a href="{{ route('customers.create') }}"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Add Customer</h3>
                        </div>
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.orders.create') }}"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Create Order</h3>
                            </div>
                        </a>
                    @elseif(auth()->user()->role === 'retailer')
                         <!-- Retailers create orders via Cart, usually -->
                         <a href="{{ route('cart.index') }}"
                            class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                            <div class="p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Go to Cart</h3>
                            </div>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>