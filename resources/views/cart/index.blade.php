<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($cartItems->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500 mb-6">Start adding products to your cart!</p>
                    <a href="{{ route('retailer.products.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Browse Products
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                                <div class="flex items-center gap-6">
                                    <!-- Product Image Placeholder -->
                                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                                        @if($item->product->factory)
                                            <p class="text-sm text-gray-500">Supplier: {{ $item->product->factory->company_name }}</p>
                                        @endif
                                        <p class="text-lg font-bold text-indigo-600 mt-2">${{ number_format($item->price, 2) }}</p>
                                    </div>

                                    <!-- Quantity & Actions -->
                                    <div class="flex flex-col items-end gap-3">
                                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <label class="text-sm text-gray-600">Qty:</label>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}" 
                                                class="w-20 px-2 py-1 border border-gray-300 rounded-md text-center">
                                            <button type="submit" class="px-3 py-1 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700">
                                                Update
                                            </button>
                                        </form>

                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">Subtotal</p>
                                            <p class="text-lg font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                                        </div>

                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="flex justify-between items-center">
                            <a href="{{ route('retailer.products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                ← Continue Shopping
                            </a>
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?')">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal ({{ $cartItems->count() }} items)</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax (18%)</span>
                                    <span>${{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <a href="{{ route('cart.checkout') }}" class="block w-full py-3 px-4 bg-indigo-600 text-white text-center font-semibold rounded-lg hover:bg-indigo-700 transition">
                                Proceed to Checkout
                            </a>

                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm text-blue-800">
                                        <p class="font-medium">Free Shipping</p>
                                        <p>On orders over $500</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
