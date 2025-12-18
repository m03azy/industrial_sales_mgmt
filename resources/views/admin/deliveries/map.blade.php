<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Live Delivery Map') }}
            </h2>
            <a href="{{ route('admin.deliveries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
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
                            <p class="mt-1 text-sm text-gray-500">There are currently no assigned or in-transit deliveries with location data to display.</p>
                        </div>
                    @else
                        <div id="deliveries-map" style="height: 600px; width: 100%; border-radius: 8px;"></div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <div class="flex items-center">
                                    <img src="https://maps.google.com/mapfiles/ms/icons/green-dot.png" class="h-6 w-6 mr-2">
                                    <span class="font-semibold text-blue-900">In Transit</span>
                                </div>
                            </div>
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <div class="flex items-center">
                                    <img src="https://maps.google.com/mapfiles/ms/icons/red-dot.png" class="h-6 w-6 mr-2">
                                    <span class="font-semibold text-yellow-900">Pending / Assigned</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <span class="text-sm text-gray-600">Total Visible: <strong>{{ $deliveries->count() }}</strong></span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function initMap() {
            const mapElement = document.getElementById('deliveries-map');
            if (!mapElement) return;

            const deliveries = @json($deliveries);
            
            // Initial center (fallback)
            const map = new google.maps.Map(mapElement, {
                zoom: 12,
                center: { lat: 0, lng: 0 },
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
            });

            const bounds = new google.maps.LatLngBounds();
            const infowindow = new google.maps.InfoWindow();

            deliveries.forEach(delivery => {
                if (delivery.delivery_latitude && delivery.delivery_longitude) {
                    const position = {
                        lat: parseFloat(delivery.delivery_latitude),
                        lng: parseFloat(delivery.delivery_longitude)
                    };

                    const markerLabel = delivery.driver && delivery.driver.user ? delivery.driver.user.name : 'Unassigned';
                    
                    // Color logic: Green for Moving, Red for Stopped/Pending
                    let iconUrl = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
                    if (delivery.status === 'in_transit') {
                        iconUrl = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
                    }

                    const marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: `Order #${delivery.order.order_number}`,
                        icon: { url: iconUrl }
                    });

                    bounds.extend(position);

                    const content = `
                        <div class="p-2 min-w-[200px]">
                            <h3 class="font-bold text-lg mb-1">Order #${delivery.order.order_number}</h3>
                            <div class="text-sm space-y-1">
                                <p><span class="font-semibold">Status:</span> 
                                    <span class="px-2 py-0.5 rounded-full text-xs ${
                                        delivery.status === 'in_transit' ? 'bg-green-100 text-green-800' : 
                                        'bg-yellow-100 text-yellow-800'
                                    }">${delivery.status.replace('_', ' ').toUpperCase()}</span>
                                </p>
                                <p><span class="font-semibold">Driver:</span> ${markerLabel}</p>
                                <p><span class="font-semibold">Address:</span> ${delivery.delivery_address || 'N/A'}</p>
                            </div>
                            <div class="mt-3 pt-2 border-t text-center">
                                <a href="/admin/deliveries/${delivery.id}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Full Details</a>
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
                // Adjust zoom if too zoomed in (single point)
                const listener = google.maps.event.addListener(map, "idle", function() { 
                    if (map.getZoom() > 16) map.setZoom(16); 
                    google.maps.event.removeListener(listener); 
                });
            } else {
                // If no valid bounds, center reasonably (e.g., attempt user location or default)
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        map.setCenter({
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        });
                        map.setZoom(12);
                    });
                }
            }
        }

        if (typeof google !== 'undefined' && google.maps) {
            initMap();
        } else {
            window.addEventListener('google-maps-loaded', initMap);
        }
    </script>
    @endpush
</x-app-layout>
