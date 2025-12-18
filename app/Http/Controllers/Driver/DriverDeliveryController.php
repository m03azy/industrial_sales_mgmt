<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DriverDeliveryController extends Controller
{
    /**
     * Display a listing of the driver's assigned deliveries.
     */
    public function index()
    {
        $driver = Auth::user()->driver;
        
        if (!$driver) {
            return redirect()->route('dashboard')->with('error', 'Driver profile not found.');
        }

        $deliveries = Delivery::where('driver_id', $driver->id)
            ->whereIn('status', ['assigned', 'in_transit'])
            ->with('order')
            ->orderBy('pickup_time', 'asc')
            ->get();

        $completedDeliveries = Delivery::where('driver_id', $driver->id)
            ->whereIn('status', ['delivered', 'canceled'])
            ->with('order')
            ->orderBy('delivered_at', 'desc')
            ->limit(10)
            ->get();

        return view('driver.deliveries.index', compact('deliveries', 'completedDeliveries'));
    }

    /**
     * Display a map of the driver's assigned deliveries.
     */
    public function map()
    {
        $driver = Auth::user()->driver;
        
        if (!$driver) {
            return redirect()->route('dashboard')->with('error', 'Driver profile not found.');
        }

        $deliveries = Delivery::where('driver_id', $driver->id)
            ->whereNotNull('delivery_latitude')
            ->whereNotNull('delivery_longitude')
            ->whereIn('status', ['assigned', 'in_transit'])
            ->with('order')
            ->get();

        return view('driver.deliveries.map', compact('deliveries'));
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery)
    {
        // Ensure the delivery belongs to the authenticated driver
        if ($delivery->driver_id !== Auth::user()->driver->id) {
            abort(403);
        }

        $delivery->load('order.retailer');
        return view('driver.deliveries.show', compact('delivery'));
    }

    /**
     * Update the delivery status and handle signature upload.
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        if ($delivery->driver_id !== Auth::user()->driver->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:in_transit,delivered,canceled',
            'signature' => 'nullable|string', // Base64 encoded image
        ]);

        $delivery->status = $validated['status'];

        if ($validated['status'] === 'delivered') {
            $delivery->delivered_at = now();

            if ($request->filled('signature')) {
                // Handle base64 signature upload
                $image = $request->input('signature');
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = 'signatures/delivery_' . $delivery->id . '_' . time() . '.png';
                
                Storage::disk('public')->put($imageName, base64_decode($image));
                $delivery->proof_of_delivery = $imageName;
            }
        }

        $delivery->save();

        // Update order status if delivery is completed
        if ($validated['status'] === 'delivered') {
            $delivery->order->update(['status' => 'delivered']);
        }

        return redirect()->route('driver.deliveries.show', $delivery)
            ->with('success', 'Delivery status updated successfully.');
    }
}
