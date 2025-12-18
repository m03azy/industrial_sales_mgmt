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
                                    <div class="flex gap-2">
                                        <textarea id="delivery_address" name="delivery_address" rows="3" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Enter your complete delivery address">{{ old('delivery_address', $retailer->address) }}</textarea>
                                        <button type="button" onclick="getCurrentLocation()" class="mt-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md border border-gray-300 h-fit" title="Use my current location">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="delivery_latitude" id="delivery_latitude" value="{{ old('delivery_latitude') }}">
                                    <input type="hidden" name="delivery_longitude" id="delivery_longitude" value="{{ old('delivery_longitude') }}">
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

@push('scripts')
<script>
    function getCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation is not supported by your browser');
            return;
        }

        const button = event.currentTarget;
        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                document.getElementById('delivery_latitude').value = lat;
                document.getElementById('delivery_longitude').value = lng;
                
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        document.getElementById('delivery_address').value = results[0].formatted_address;
                    }
                    button.disabled = false;
                    button.innerHTML = originalHtml;
                });
            },
            (error) => {
                alert('Unable to retrieve your location');
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        );
    }

    function initAutocomplete() {
        const input = document.getElementById('delivery_address');
        const autocomplete = new google.maps.places.Autocomplete(input);
        
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                document.getElementById('delivery_latitude').value = place.geometry.location.lat();
                document.getElementById('delivery_longitude').value = place.geometry.location.lng();
            }
        });
    }

    if (typeof google !== 'undefined' && google.maps && google.maps.places) {
        initAutocomplete();
    } else {
        window.addEventListener('google-maps-loaded', initAutocomplete);
    }
</script>
@endpush
