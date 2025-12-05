<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Delivery') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.deliveries.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Order Selection -->
                            <div>
                                <label for="order_id" class="block text-sm font-medium text-gray-700">Order</label>
                                <select name="order_id" id="order_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select Order</option>
                                    @foreach($orders as $id => $order_number)
                                        <option value="{{ $id }}">{{ $order_number }}</option>
                                    @endforeach
                                </select>
                                @error('order_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Driver Selection -->
                            <div>
                                <label for="driver_id" class="block text-sm font-medium text-gray-700">Driver</label>
                                <select name="driver_id" id="driver_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Unassigned</option>
                                    @foreach($drivers as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="pending">Pending</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="in_transit">In Transit</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="canceled">Canceled</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Delivery Price</label>
                                <input type="number" name="price" id="price" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Pickup Time -->
                            <div>
                                <label for="pickup_time" class="block text-sm font-medium text-gray-700">Pickup Time</label>
                                <input type="datetime-local" name="pickup_time" id="pickup_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('pickup_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Delivery Time -->
                            <div>
                                <label for="delivery_time" class="block text-sm font-medium text-gray-700">Estimated Delivery Time</label>
                                <input type="datetime-local" name="delivery_time" id="delivery_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('delivery_time')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Fields -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Locations</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Pickup Address -->
                                <div>
                                    <label for="pickup_address" class="block text-sm font-medium text-gray-700">Pickup Address</label>
                                    <input type="text" name="pickup_address" id="pickup_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter pickup location">
                                    <input type="hidden" name="pickup_latitude" id="pickup_latitude">
                                    <input type="hidden" name="pickup_longitude" id="pickup_longitude">
                                    @error('pickup_address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Delivery Address -->
                                <div>
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700">Delivery Address</label>
                                    <input type="text" name="delivery_address" id="delivery_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter delivery location">
                                    <input type="hidden" name="delivery_latitude" id="delivery_latitude">
                                    <input type="hidden" name="delivery_longitude" id="delivery_longitude">
                                    @error('delivery_address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div id="distance-info" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                                <p class="text-sm text-blue-800"><span class="font-semibold">Distance:</span> <span id="distance-display">-</span> km</p>
                                <p class="text-sm text-blue-800"><span class="font-semibold">Estimated Duration:</span> <span id="duration-display">-</span> minutes</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('admin.deliveries.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md mr-2 hover:bg-gray-300">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Create Delivery</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    @push('scripts')
    <script>
        function initAutocomplete() {
            const pickupInput = document.getElementById('pickup_address');
            const deliveryInput = document.getElementById('delivery_address');
            
            const pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);
            const deliveryAutocomplete = new google.maps.places.Autocomplete(deliveryInput);
            
            pickupAutocomplete.addListener('place_changed', () => {
                const place = pickupAutocomplete.getPlace();
                if (place.geometry) {
                    document.getElementById('pickup_latitude').value = place.geometry.location.lat();
                    document.getElementById('pickup_longitude').value = place.geometry.location.lng();
                    calculateDistance();
                }
            });
            
            deliveryAutocomplete.addListener('place_changed', () => {
                const place = deliveryAutocomplete.getPlace();
                if (place.geometry) {
                    document.getElementById('delivery_latitude').value = place.geometry.location.lat();
                    document.getElementById('delivery_longitude').value = place.geometry.location.lng();
                    calculateDistance();
                }
            });
        }

        function calculateDistance() {
            const pickupLat = document.getElementById('pickup_latitude').value;
            const pickupLng = document.getElementById('pickup_longitude').value;
            const deliveryLat = document.getElementById('delivery_latitude').value;
            const deliveryLng = document.getElementById('delivery_longitude').value;

            if (pickupLat && pickupLng && deliveryLat && deliveryLng) {
                const origin = new google.maps.LatLng(pickupLat, pickupLng);
                const destination = new google.maps.LatLng(deliveryLat, deliveryLng);

                const service = new google.maps.DistanceMatrixService();
                service.getDistanceMatrix(
                    {
                        origins: [origin],
                        destinations: [destination],
                        travelMode: 'DRIVING',
                    },
                    (response, status) => {
                        if (status === 'OK' && response.rows[0].elements[0].status === 'OK') {
                            const distance = response.rows[0].elements[0].distance;
                            const duration = response.rows[0].elements[0].duration;
                            
                            // Update display
                            document.getElementById('distance-info').classList.remove('hidden');
                            document.getElementById('distance-display').textContent = (distance.value / 1000).toFixed(1);
                            document.getElementById('duration-display').textContent = Math.round(duration.value / 60);
                            
                            // Update hidden fields (if we were submitting them, but we might calculate on backend too)
                            // For now, let's auto-calculate price based on distance if needed
                            const distanceKm = distance.value / 1000;
                            const basePrice = 50; // Base price
                            const pricePerKm = 10; // Price per km
                            const estimatedPrice = basePrice + (distanceKm * pricePerKm);
                            
                            const priceInput = document.getElementById('price');
                            if (!priceInput.value) {
                                priceInput.value = estimatedPrice.toFixed(2);
                            }
                        }
                    }
                );
            }
        }

        // Initialize when Google Maps is loaded
        if (typeof google !== 'undefined' && google.maps && google.maps.places) {
            initAutocomplete();
        } else {
            window.addEventListener('google-maps-loaded', initAutocomplete);
        }
    </script>
    @endpush
</x-app-layout>
