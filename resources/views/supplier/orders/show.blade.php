<x-app-layout>
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl">Purchase Order {{ $purchaseOrder->order_number }}</h2>
            </div>

            <dl>
                <dt class="font-semibold">Order Date</dt>
                <dd class="mb-2">{{ $purchaseOrder->order_date }}</dd>

                <dt class="font-semibold">Status</dt>
                <dd class="mb-2">{{ ucfirst($purchaseOrder->status) }}</dd>

                <dt class="font-semibold">Items</dt>
                <dd class="mb-2">
                    <ul>
                        @foreach($purchaseOrder->items as $it)
                            <li>{{ $it->product->name ?? 'Product #' . $it->product_id }} — Qty: {{ $it->quantity }}</li>
                        @endforeach
                    </ul>
                </dd>
            </dl>

            <form action="{{ route('supplier.orders.ship', $purchaseOrder) }}" method="POST" class="mt-4">
                @csrf
                <button class="btn btn-primary">Mark as Shipped</button>
            </form>
            <form action="{{ route('supplier.orders.deliver', $purchaseOrder) }}" method="POST" class="mt-2">
                @csrf
                <button class="btn btn-green">Mark as Delivered</button>
            </form>
        </div>
    </div>
</x-app-layout>
