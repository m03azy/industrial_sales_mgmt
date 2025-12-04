<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $order->order_number }}</h2>
            <div>
                <a href="{{ route('purchase-orders.edit', $order) }}" class="text-indigo-600">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-start">
                    <dl>
                    <dt class="font-semibold">Supplier</dt>
                    <dd class="mb-2">{{ optional($order->supplier)->company_name ?? 'N/A' }}</dd>

                    <dt class="font-semibold">Date</dt>
                    <dd class="mb-2">{{ $order->order_date }}</dd>

                    <dt class="font-semibold">Total</dt>
                    <dd class="mb-2">${{ number_format($order->total_amount,2) }}</dd>
                    </dl>

                    <div class="ms-4">
                        @if($order->status !== 'received')
                            <form method="POST" action="{{ route('purchase-orders.receive', $order) }}" onsubmit="return confirm('Mark this PO as received and update inventory?')">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded">Mark Received</button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-3 py-2 bg-gray-200 rounded">Received</span>
                        @endif
                    </div>

                </div>

                <h3 class="mt-4 font-semibold">Items</h3>
                <ul class="list-disc list-inside">
                    @foreach($order->items as $it)
                        <li>{{ $it->product->name ?? 'Deleted' }} — {{ $it->quantity }} × ${{ number_format($it->unit_price,2) }} = ${{ number_format($it->total_price,2) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
