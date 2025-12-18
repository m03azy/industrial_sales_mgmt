<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Deliveries Map') }}
            </h2>
            <a href="{{ route('driver.deliveries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($deliveries->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 7m0 13V7m0 0L9 4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Active Deliveries</h3>
                            <p class="mt-1 text-sm text-gray-500">You don't have any assigned deliveries with location data to display.</p>
                        </div>
                    @else
                        <div id="driver-map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
                        <div class="mt-4 bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">Showing <strong>{{ $deliveries->count() }}</strong> delivery locations.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function initDriverMap() {
            const mapElement = document.getElementById('driver-map');
            if (!mapElement) return;

            const deliveries = @json($deliveries);
            
            const map = new google.maps.Map(mapElement, {
                zoom: 12,
                center: { lat: 0, lng: 0 },
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
            });

            const bounds = new google.maps.LatLngBounds();
            const infowindow = new google.maps.InfoWindow();

            // Try to center on user's current location first
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userPos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    // Add a blue dot for current driver position
                    new google.maps.Marker({
                        position: userPos,
                        map: map,
                        title: "Current Location",
                        icon: {
                            path: google.maps.SymbolPath.CIRCLE,
                            scale: 10,
                            fillColor: "#4285F4",
                            fillOpacity: 1,
                            strokeWeight: 2,
                            strokeColor: "white",
                        }
                    });
                    
                    // If no deliveries, markers, center here
                    if (deliveries.length === 0) {
                        map.setCenter(userPos);
                    }
                });
            }

            deliveries.forEach(delivery => {
                if (delivery.delivery_latitude && delivery.delivery_longitude) {
                    const position = {
                        lat: parseFloat(delivery.delivery_latitude),
                        lng: parseFloat(delivery.delivery_longitude)
                    };

                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: `Order #${delivery.order.order_number}`,
                        animation: google.maps.Animation.DROP
                    });

                    bounds.extend(position);

                    const content = `
                        <div class="p-2 min-w-[200px]">
                            <h3 class="font-bold text-lg mb-1">Order #${delivery.order.order_number}</h3>
                            <div class="text-sm space-y-2">
                                <p class="bg-gray-100 p-2 rounded">${delivery.delivery_address || 'No address'}</p>
                                <div class="flex justify-between items-center pt-2">
                                    <span class="text-gray-500">${delivery.status.toUpperCase()}</span>
                                    <a href="/driver/deliveries/${delivery.id}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">View Job</a>
                                </div>
                            </div>
                        </div>
                    `;

                    marker.addListener('click', () => {
                        infowindow.setContent(content);
                        infowindow.open(map, marker);
                    });
                }
            });

            if (deliveries.length > 0) {
                map.fitBounds(bounds);
                const listener = google.maps.event.addListener(map, "idle", function() { 
                    if (map.getZoom() > 16) map.setZoom(16); 
                    google.maps.event.removeListener(listener); 
                });
            }
        }

        if (typeof google !== 'undefined' && google.maps) {
            initDriverMap();
        } else {
            window.addEventListener('google-maps-loaded', initDriverMap);
        }
    </script>
    @endpush
</x-app-layout>
