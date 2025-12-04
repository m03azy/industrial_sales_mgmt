<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dispute Details') }} #{{ $dispute->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dispute Info -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Dispute Information</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($dispute->status === 'open') bg-red-100 text-red-800
                                @elseif($dispute->status === 'resolved') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($dispute->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Reason</p>
                            <p class="font-medium text-gray-900">{{ $dispute->reason }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Description</p>
                            <p class="text-gray-900 bg-gray-50 p-3 rounded">{{ $dispute->description }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Retailer (Reporter)</p>
                            <p class="font-medium text-gray-900">{{ $dispute->retailer->name }} ({{ $dispute->retailer->email }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Factory (Involved)</p>
                            <p class="font-medium text-gray-900">{{ $dispute->factory->factory_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Order</p>
                            <a href="{{ route('orders.show', $dispute->order_id) }}" class="text-indigo-600 hover:underline">
                                {{ $dispute->order->order_number ?? 'N/A' }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Resolution -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Resolution</h3>
                    @if($dispute->status === 'open')
                        <form action="{{ route('admin.disputes.resolve', $dispute) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Resolution Details</label>
                                <textarea name="resolution" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Action</label>
                                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="resolved">Mark as Resolved</option>
                                    <option value="closed">Close Dispute</option>
                                </select>
                            </div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-full">Submit Resolution</button>
                        </form>
                    @else
                        <div class="bg-gray-50 p-4 rounded">
                            <p class="text-sm text-gray-500">Resolution</p>
                            <p class="text-gray-900">{{ $dispute->resolution }}</p>
                            <p class="text-xs text-gray-400 mt-2">Resolved on {{ $dispute->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
