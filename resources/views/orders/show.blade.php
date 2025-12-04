<x-app-layout>
    @php
        $routePrefix = auth()->user()->role === 'admin' ? 'admin.orders' : 'retailer.orders';
    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $order->order_number }}</h2>
                    <div class="space-x-2">
                <a href="{{ route($routePrefix . '.edit', $order) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                <form action="{{ route($routePrefix . '.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                </form>
                <a href="{{ route('orders.invoice', $order) }}" class="ml-2 inline-flex items-center px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">View Invoice</a>
                <a href="{{ route('orders.invoice.pdf', $order) }}" class="ml-2 inline-flex items-center px-3 py-1 bg-gray-800 text-white rounded hover:bg-gray-900">Download PDF</a>

                <form action="{{ route('orders.invoice.generate', $order) }}" method="POST" class="inline ms-2">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Generate PDF</button>
                </form>
                </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Customer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->customer->company_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->order_date }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($order->status) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Items</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <ul class="list-disc list-inside">
                                    @foreach($order->orderItems as $item)
                                        <li>{{ $item->product->name ?? 'Deleted product' }} — {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }} = ${{ number_format($item->total_price, 2) }}</li>
                                    @endforeach
                                </ul>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
