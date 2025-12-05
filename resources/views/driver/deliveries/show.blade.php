<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Delivery Details') }} #{{ $delivery->order->order_number }}
            </h2>
            <a href="{{ route('driver.deliveries.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                &larr; Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Status Banner -->
                    <div class="mb-6 p-4 rounded-lg flex justify-between items-center
                        {{ $delivery->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                           ($delivery->status === 'canceled' ? 'bg-red-100 text-red-800' : 'bg-blue-50 text-blue-800') }}">
                        <div>
                            <span class="font-bold text-lg">Status: {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span>
                            @if($delivery->delivered_at)
                                <p class="text-sm mt-1">Delivered at: {{ $delivery->delivered_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                        
                        @if($delivery->status === 'assigned')
                            <form action="{{ route('driver.deliveries.update-status', $delivery) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="in_transit">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Start Delivery
                                </button>
                            </form>
                        @elseif($delivery->status === 'in_transit')
                            <button onclick="openSignatureModal()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                Complete Delivery
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Delivery Info -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Pickup Location</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="font-medium">{{ $delivery->order->retailer->company_name ?? 'Warehouse' }}</p>
                                    <p class="text-gray-600">{{ $delivery->pickup_address }}</p>
                                    @if($delivery->pickup_time)
                                        <p class="text-sm text-gray-500 mt-2">Scheduled: {{ $delivery->pickup_time->format('M d, H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-2">Delivery Location</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="font-medium">{{ $delivery->order->user->name ?? 'Customer' }}</p>
                                    <p class="text-gray-600">{{ $delivery->delivery_address }}</p>
                                    @if($delivery->delivery_time)
                                        <p class="text-sm text-gray-500 mt-2">Estimated: {{ $delivery->delivery_time->format('M d, H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-2">Order Summary</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p><span class="font-medium">Items:</span> {{ $delivery->order->orderItems->count() }} items</p>
                                    <p><span class="font-medium">Total Weight:</span> {{ $delivery->order->total_weight ?? 'N/A' }} kg</p>
                                    <p class="mt-2 text-sm text-gray-500">{{ $delivery->order->delivery_notes }}</p>
                                </div>
                            </div>

                            @if($delivery->proof_of_delivery)
                                <div>
                                    <h3 class="text-lg font-semibold mb-2">Proof of Delivery</h3>
                                    <div class="border rounded-lg p-2">
                                        <img src="{{ asset('storage/' . $delivery->proof_of_delivery) }}" alt="Customer Signature" class="max-w-full h-auto">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Map -->
                        <div class="h-96 bg-gray-100 rounded-lg overflow-hidden">
                            <x-delivery-map :delivery="$delivery" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Modal -->
    <div id="signatureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Customer Signature</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4">Please ask the customer to sign below to confirm receipt.</p>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-1 bg-gray-50">
                        <canvas id="signaturePad" class="w-full h-48 bg-white cursor-crosshair"></canvas>
                    </div>
                    
                    <div class="flex justify-between mt-2 text-sm">
                        <button type="button" onclick="clearSignature()" class="text-red-600 hover:text-red-800">Clear</button>
                    </div>
                </div>
                
                <div class="items-center px-4 py-3">
                    <form action="{{ route('driver.deliveries.update-status', $delivery) }}" method="POST" id="deliveryForm">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="delivered">
                        <input type="hidden" name="signature" id="signatureInput">
                        
                        <button type="button" onclick="submitDelivery()" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-300">
                            Confirm Delivery
                        </button>
                        <button type="button" onclick="closeSignatureModal()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        let signaturePad;

        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signaturePad');
            
            // Handle canvas resizing for responsiveness
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            
            window.onresize = resizeCanvas;
            resizeCanvas();

            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)'
            });
        });

        function openSignatureModal() {
            document.getElementById('signatureModal').classList.remove('hidden');
            // Resize again just in case modal display affected dimensions
            const canvas = document.getElementById('signaturePad');
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        function closeSignatureModal() {
            document.getElementById('signatureModal').classList.add('hidden');
        }

        function clearSignature() {
            signaturePad.clear();
        }

        function submitDelivery() {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                return;
            }

            const dataUrl = signaturePad.toDataURL();
            document.getElementById('signatureInput').value = dataUrl;
            document.getElementById('deliveryForm').submit();
        }
    </script>
    @endpush
</x-app-layout>
