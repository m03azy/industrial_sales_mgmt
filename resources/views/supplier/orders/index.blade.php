<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl">Your Purchase Orders</h2>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b">
                        <th class="py-2">Order #</th>
                        <th class="py-2">Date</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Total</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $o)
                        <tr class="border-b">
                            <td class="py-2">{{ $o->order_number }}</td>
                            <td class="py-2">{{ $o->order_date }}</td>
                            <td class="py-2">{{ ucfirst($o->status) }}</td>
                            <td class="py-2">${{ number_format($o->total_amount,2) }}</td>
                            <td class="py-2">
                                <a href="{{ route('supplier.orders.show', $o) }}" class="text-indigo-600">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4">No purchase orders assigned.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>
