@props(['delivery'])

<div class="delivery-map-container">
    <div id="delivery-map-{{ $delivery->id }}" style="height: 400px; width: 100%; border-radius: 8px;"></div>
    
    @if($delivery->distance_km)
        <div class="mt-4 grid grid-cols-2 gap-4">
            <div class="bg-blue-50 p-3 rounded-lg">
                <p class="text-sm text-blue-600 font-medium">Distance</p>
                <p class="text-2xl font-bold text-blue-900">{{ number_format($delivery->distance_km, 1) }} km</p>
            </div>
            @if($delivery->estimated_duration_minutes)
                <div class="bg-green-50 p-3 rounded-lg">
                    <p class="text-sm text-green-600 font-medium">Est. Duration</p>
                    <p class="text-2xl font-bold text-green-900">{{ $delivery->estimated_duration_minutes }} min</p>
                </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
function initDeliveryMap{{ $delivery->id }}() {
    const mapElement = document.getElementById('delivery-map-{{ $delivery->id }}');
    if (!mapElement) return;

    // Check if we have coordinates
    const hasPickup = {{ $delivery->pickup_latitude && $delivery->pickup_longitude ? 'true' : 'false' }};
    const hasDelivery = {{ $delivery->delivery_latitude && $delivery->delivery_longitude ? 'true' : 'false' }};

    if (!hasPickup && !hasDelivery) {
        mapElement.innerHTML = '<div class="flex items-center justify-center h-full bg-gray-100 rounded-lg"><p class="text-gray-500">No location data available</p></div>';
        return;
    }

    const pickup = hasPickup ? {
        lat: {{ $delivery->pickup_latitude ?? 0 }},
        lng: {{ $delivery->pickup_longitude ?? 0 }}
    } : null;

    const delivery = hasDelivery ? {
        lat: {{ $delivery->delivery_latitude ?? 0 }},
        lng: {{ $delivery->delivery_longitude ?? 0 }}
    } : null;

    // Initialize map
    const center = pickup || delivery;
    const map = new google.maps.Map(mapElement, {
        zoom: 12,
        center: center,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true,
    });

    // Add pickup marker
    if (pickup) {
        const pickupMarker = new google.maps.Marker({
            position: pickup,
            map: map,
            title: 'Pickup Location',
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }
        });

        const pickupInfo = new google.maps.InfoWindow({
            content: `
                <div style="padding: 8px;">
                    <h3 style="font-weight: bold; margin-bottom: 4px;">Pickup Location</h3>
                    <p style="margin: 0;">{{ $delivery->pickup_address ?? 'Address not set' }}</p>
                </div>
            `
        });

        pickupMarker.addListener('click', () => {
            pickupInfo.open(map, pickupMarker);
        });
    }

    // Add delivery marker
    if (delivery) {
        const deliveryMarker = new google.maps.Marker({
            position: delivery,
            map: map,
            title: 'Delivery Location',
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        const deliveryInfo = new google.maps.InfoWindow({
            content: `
                <div style="padding: 8px;">
                    <h3 style="font-weight: bold; margin-bottom: 4px;">Delivery Location</h3>
                    <p style="margin: 0;">{{ $delivery->delivery_address ?? 'Address not set' }}</p>
                </div>
            `
        });

        deliveryMarker.addListener('click', () => {
            deliveryInfo.open(map, deliveryMarker);
        });
    }

    // Draw route if both locations exist
    if (pickup && delivery) {
        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            map: map,
            suppressMarkers: true, // We already have custom markers
            polylineOptions: {
                strokeColor: '#4F46E5',
                strokeWeight: 4
            }
        });

        directionsService.route({
            origin: pickup,
            destination: delivery,
            travelMode: google.maps.TravelMode.DRIVING
        }, (result, status) => {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
            }
        });

        // Fit bounds to show both markers
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(pickup);
        bounds.extend(delivery);
        map.fitBounds(bounds);
    }
}

// Initialize when Google Maps is loaded
if (typeof google !== 'undefined' && google.maps) {
    initDeliveryMap{{ $delivery->id }}();
} else {
    window.addEventListener('google-maps-loaded', () => {
        initDeliveryMap{{ $delivery->id }}();
    });
}
</script>
@endpush
