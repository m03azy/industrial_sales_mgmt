<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('cart.process-checkout') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Checkout Form -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Delivery Information -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Information</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address *</label>
                                    <textarea id="delivery_address" name="delivery_address" rows="3" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Enter your complete delivery address">{{ old('delivery_address', $retailer->address) }}</textarea>
                                    @error('delivery_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="delivery_notes" class="block text-sm font-medium text-gray-700 mb-2">Delivery Notes (Optional)</label>
                                    <textarea id="delivery_notes" name="delivery_notes" rows="2"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Any special instructions for delivery">{{ old('delivery_notes') }}</textarea>
                                    @error('delivery_notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                    <input type="radio" name="payment_method" value="cash" class="w-4 h-4 text-indigo-600" {{ old('payment_method') == 'cash' ? 'checked' : '' }} required>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Cash on Delivery</p>
                                        <p class="text-sm text-gray-500">Pay when you receive your order</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="w-4 h-4 text-indigo-600" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Bank Transfer</p>
                                        <p class="text-sm text-gray-500">Transfer to our bank account</p>
                                    </div>
                                </label>

                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                    <input type="radio" name="payment_method" value="mobile_money" class="w-4 h-4 text-indigo-600" {{ old('payment_method') == 'mobile_money' ? 'checked' : '' }}>
                                    <div class="ml-3">
                                        <p class="font-medium text-gray-900">Mobile Money</p>
                                        <p class="text-sm text-gray-500">M-Pesa, Airtel Money, etc.</p>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Order Items Review -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items ({{ $cartItems->count() }})</h3>
                            
                            <div class="space-y-3">
                                @foreach($cartItems as $item)
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</p>
                                        </div>
                                        <p class="font-semibold text-gray-900">${{ number_format($item->subtotal, 2) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 sticky top-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                            
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax (18%)</span>
                                    <span>${{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span class="text-green-600 font-medium">FREE</span>
                                </div>
                                <div class="border-t pt-3 flex justify-between text-xl font-bold text-gray-900">
                                    <span>Total</span>
                                    <span>${{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition mb-3">
                                Place Order
                            </button>

                            <a href="{{ route('cart.index') }}" class="block w-full py-3 px-4 bg-gray-200 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-300 transition">
                                Back to Cart
                            </a>

                            <div class="mt-6 p-4 bg-green-50 rounded-lg">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-sm text-green-800">
                                        <p class="font-medium">Secure Checkout</p>
                                        <p>Your information is protected</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
