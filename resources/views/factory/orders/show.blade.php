<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }} - {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Order Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Order Number</h3>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Retailer</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $order->retailer->company_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Order Date</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $order->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Status</h3>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'shipped') bg-blue-100 text-blue-800
                                @elseif($order->status === 'confirmed') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items (Factory Products Only) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Your Products in This Order</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $factoryTotal = 0; @endphp
                            @foreach($order->orderItems as $item)
                                @if($item->product->factory_id === auth()->user()->factory->id)
                                    @php $factoryTotal += $item->total_price; @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($item->product->image)
                                                    <img class="h-10 w-10 rounded object-cover mr-3" src="{{ asset('storage/' . $item->product->image) }}" alt="">
                                                @endif
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-semibold">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Your Products Total:</td>
                                <td class="px-6 py-4 text-right text-lg font-bold text-indigo-600">${{ number_format($factoryTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Update Status -->
            @if($order->status === 'confirmed')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Update Order Status</h3>
                    <form action="{{ route('factory.orders.update-status', $order) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="shipped">
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                            Mark as Shipped
                        </button>
                    </form>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-between">
                <a href="{{ route('factory.orders.index') }}" class="text-indigo-600 hover:text-indigo-900">
                    ← Back to Orders
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
