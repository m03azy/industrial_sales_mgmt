<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\SalesOrder;
use App\Models\Driver;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the deliveries.
     */
    public function index()
    {
        $deliveries = Delivery::with(['order', 'driver'])->paginate(15);
        return view('admin.deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new delivery.
     */
    public function create()
    {
        $orders = SalesOrder::where('status', 'pending')->pluck('id', 'id');
        $drivers = Driver::with('user')->get()->pluck('user.name', 'id');
        return view('admin.deliveries.create', compact('orders', 'drivers'));
    }

    /**
     * Store a newly created delivery in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|in:pending,assigned,in_transit,delivered,canceled',
            'pickup_time' => 'nullable|date',
            'delivery_time' => 'nullable|date',
            'distance_km' => 'nullable|numeric',
            'price' => 'nullable|numeric',
        ]);
        Delivery::create($validated);
        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery created successfully.');
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['order', 'driver']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified delivery.
     */
    public function edit(Delivery $delivery)
    {
        $orders = SalesOrder::pluck('id', 'id');
        $drivers = Driver::with('user')->get()->pluck('user.name', 'id');
        return view('admin.deliveries.edit', compact('delivery', 'orders', 'drivers'));
    }

    /**
     * Update the specified delivery in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'status' => 'required|in:pending,assigned,in_transit,delivered,canceled',
            'pickup_time' => 'nullable|date',
            'delivery_time' => 'nullable|date',
            'distance_km' => 'nullable|numeric',
            'price' => 'nullable|numeric',
        ]);
        $delivery->update($validated);
        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery updated successfully.');
    }

    /**
     * Remove the specified delivery from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('admin.deliveries.index')->with('success', 'Delivery deleted successfully.');
    }
}
