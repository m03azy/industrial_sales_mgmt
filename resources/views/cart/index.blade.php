<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Your Cart') }}</h2>
            <a href="{{ route('products.index') }}" class="text-sm text-indigo-600">Continue shopping</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))<div class="mb-4 text-green-700">{{ session('success') }}</div>@endif
                    @if(empty($items))
                        <p class="text-center text-gray-500">Your cart is empty.</p>
                    @else
                        <form method="POST" action="{{ route('cart.update') }}">
                            @csrf
                            <table class="min-w-full divide-y divide-gray-200 mb-4">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left">Product</th>
                                        <th class="px-6 py-3 text-left">Qty</th>
                                        <th class="px-6 py-3 text-left">Unit</th>
                                        <th class="px-6 py-3 text-right">Line</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $it)
                                        <tr class="border-b">
                                            <td class="px-6 py-3">{{ $it['product']->name }}</td>
                                            <td class="px-6 py-3">
                                                <input type="number" name="quantities[{{ $it['product']->id }}]" value="{{ $it['quantity'] }}" min="1" class="w-20">
                                            </td>
                                            <td class="px-6 py-3">${{ number_format($it['unit_price'],2) }}</td>
                                            <td class="px-6 py-3 text-right">${{ number_format($it['line_total'],2) }}</td>
                                            <td class="px-6 py-3 text-right">
                                                <form method="POST" action="{{ route('cart.remove') }}">@csrf
                                                    <input type="hidden" name="product_id" value="{{ $it['product']->id }}">
                                                    <button class="text-red-600">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <button class="bg-gray-200 px-3 py-2 rounded" type="submit">Update cart</button>
                                </div>
                                <div class="text-lg font-bold">Total: ${{ number_format($total,2) }}</div>
                            </div>
                        </form>

                        <div class="bg-gray-50 p-4 rounded">
                            <form method="POST" action="{{ route('cart.checkout') }}">
                                @csrf
                                <div class="mb-3">
                                    <label>Order Date</label>
                                    <input type="date" name="order_date" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Delivery Method</label>
                                    <select name="delivery_method">
                                        <option value="delivery">Delivery</option>
                                        <option value="pickup">Pickup</option>
                                    </select>
                                </div>
                                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Checkout</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
