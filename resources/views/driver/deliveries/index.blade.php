<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Deliveries') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Active Deliveries -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-indigo-700">Active Deliveries</h3>
                
                @if($deliveries->isEmpty())
                    <p class="text-gray-500 text-center py-4">No active deliveries assigned.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($deliveries as $delivery)
                            <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-lg">#{{ $delivery->order->order_number }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $delivery->status === 'assigned' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $delivery->status === 'in_transit' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2 text-sm text-gray-600 mb-4">
                                    <p><span class="font-semibold">Pickup:</span> {{ Str::limit($delivery->pickup_address, 30) }}</p>
                                    <p><span class="font-semibold">Delivery:</span> {{ Str::limit($delivery->delivery_address, 30) }}</p>
                                    <p><span class="font-semibold">Time:</span> {{ $delivery->delivery_time ? $delivery->delivery_time->format('M d, H:i') : 'ASAP' }}</p>
                                </div>

                                <a href="{{ route('driver.deliveries.show', $delivery) }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition-colors">
                                    View Details
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Completed History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">Recent History</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($completedDeliveries as $delivery)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $delivery->order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $delivery->delivered_at ? $delivery->delivered_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $delivery->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($delivery->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('driver.deliveries.show', $delivery) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No completed deliveries yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
