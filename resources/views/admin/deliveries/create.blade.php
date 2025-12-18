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
                                    <div class="flex gap-2">
                                        <input type="text" name="pickup_address" id="pickup_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter pickup location">
                                        <button type="button" onclick="getCurrentLocation('pickup')" class="mt-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md border border-gray-300" title="Use my current location">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="hidden" name="pickup_latitude" id="pickup_latitude">
                                    <input type="hidden" name="pickup_longitude" id="pickup_longitude">
                                    @error('pickup_address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Delivery Address -->
                                <div>
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700">Delivery Address</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="delivery_address" id="delivery_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter delivery location">
                                        <button type="button" onclick="getCurrentLocation('delivery')" class="mt-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md border border-gray-300" title="Use my current location">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </button>
                                    </div>
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

                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Location Preview</h3>
                            <div id="create-delivery-map" style="height: 350px; width: 100%; border-radius: 8px;" class="border"></div>
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
        function getCurrentLocation(type) {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById(type + '_latitude').value = lat;
                    document.getElementById(type + '_longitude').value = lng;
                    
                    // Reverse geocode to get address
                    const geocoder = new google.maps.Geocoder();
                    const latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
                    
                    geocoder.geocode({ location: latlng }, (results, status) => {
                        if (status === 'OK') {
                            if (results[0]) {
                                document.getElementById(type + '_address').value = results[0].formatted_address;
                                calculateDistance();
                            } else {
                                alert('No results found');
                            }
                        } else {
                            alert('Geocoder failed due to: ' + status);
                        }
                    });
                },
                (error) => {
                    alert('Unable to retrieve your location');
                }
            );
        }

        let map, pickupMarker, deliveryMarker;

        function initAutocomplete() {
            const mapElement = document.getElementById('create-delivery-map');
            if (mapElement) {
                map = new google.maps.Map(mapElement, {
                    zoom: 12,
                    center: { lat: 0, lng: 0 }
                });
            }

            const pickupInput = document.getElementById('pickup_address');
            const deliveryInput = document.getElementById('delivery_address');
            
            const pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);
            const deliveryAutocomplete = new google.maps.places.Autocomplete(deliveryInput);
            
            pickupAutocomplete.addListener('place_changed', () => {
                const place = pickupAutocomplete.getPlace();
                if (place.geometry) {
                    setMarker('pickup', place.geometry.location.lat(), place.geometry.location.lng(), place.formatted_address);
                }
            });
            
            deliveryAutocomplete.addListener('place_changed', () => {
                const place = deliveryAutocomplete.getPlace();
                if (place.geometry) {
                    setMarker('delivery', place.geometry.location.lat(), place.geometry.location.lng(), place.formatted_address);
                }
            });

            // Order Selection Handler
            document.getElementById('order_id').addEventListener('change', function() {
                const orderId = this.value;
                if (!orderId) return;

                fetch(`/admin/orders/${orderId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.delivery_address) {
                            document.getElementById('delivery_address').value = data.delivery_address;
                            if (data.delivery_latitude && data.delivery_longitude) {
                                setMarker('delivery', data.delivery_latitude, data.delivery_longitude, data.delivery_address);
                            } else {
                                // If no coordinates, just geocode the address
                                const geocoder = new google.maps.Geocoder();
                                geocoder.geocode({ address: data.delivery_address }, (results, status) => {
                                    if (status === 'OK') {
                                        setMarker('delivery', results[0].geometry.location.lat(), results[0].geometry.location.lng(), results[0].formatted_address);
                                    }
                                });
                            }
                        }
                    });
            });
        }

        function setMarker(type, lat, lng, address) {
            lat = parseFloat(lat);
            lng = parseFloat(lng);
            const pos = { lat, lng };

            if (type === 'pickup') {
                document.getElementById('pickup_latitude').value = lat;
                document.getElementById('pickup_longitude').value = lng;
                document.getElementById('pickup_address').value = address;
                
                if (pickupMarker) pickupMarker.setMap(null);
                pickupMarker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: 'Pickup',
                    icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
                });
            } else {
                document.getElementById('delivery_latitude').value = lat;
                document.getElementById('delivery_longitude').value = lng;
                document.getElementById('delivery_address').value = address;

                if (deliveryMarker) deliveryMarker.setMap(null);
                deliveryMarker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: 'Delivery',
                    icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
                });
            }

            updateMapBounds();
            calculateDistance();
        }

        function updateMapBounds() {
            const bounds = new google.maps.LatLngBounds();
            let hasPoints = false;
            if (pickupMarker) { bounds.extend(pickupMarker.getPosition()); hasPoints = true; }
            if (deliveryMarker) { bounds.extend(deliveryMarker.getPosition()); hasPoints = true; }
            
            if (hasPoints) {
                map.fitBounds(bounds);
                if (map.getZoom() > 15) map.setZoom(15);
            }
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
