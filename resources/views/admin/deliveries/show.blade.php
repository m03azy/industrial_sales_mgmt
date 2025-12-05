<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Delivery Details') }} #{{ $delivery->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Delivery Information</h3>
                        <div>
                            <a href="{{ route('admin.deliveries.edit', $delivery) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 mr-2">Edit</a>
                            <a href="{{ route('admin.deliveries.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">Back</a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Delivery Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2">Status & Tracking</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Price</p>
                                    <p class="font-medium text-gray-900">${{ number_format($delivery->price, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Pickup Time</p>
                                    <p class="font-medium text-gray-900">{{ $delivery->pickup_time ? $delivery->pickup_time->format('M d, Y H:i') : 'Not set' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Delivery Time</p>
                                    <p class="font-medium text-gray-900">{{ $delivery->delivery_time ? $delivery->delivery_time->format('M d, Y H:i') : 'Not set' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Driver Info -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-700 mb-2">Driver Details</h4>
                            @if($delivery->driver)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Name</p>
                                        <p class="font-medium text-gray-900">{{ $delivery->driver->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Vehicle</p>
                                        <p class="font-medium text-gray-900">{{ $delivery->driver->vehicle_type }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">License</p>
                                        <p class="font-medium text-gray-900">{{ $delivery->driver->license_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Contact</p>
                                        <p class="font-medium text-gray-900">{{ $delivery->driver->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No driver assigned.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-700 mb-2">Order Details</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Order Number</p>
                                <p class="font-medium text-gray-900">
                                    <a href="{{ route('orders.show', $delivery->order_id) }}" class="text-indigo-600 hover:underline">
                                        {{ $delivery->order->order_number ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Customer</p>
                                <p class="font-medium text-gray-900">{{ $delivery->order->customer->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Route Map -->
                    <div class="mt-6">
                        <h4 class="font-semibold text-gray-700 mb-4 text-lg">Delivery Route</h4>
                        <x-delivery-map :delivery="$delivery" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
