<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SalesOrder;
use App\Models\Driver;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'driver_id',
        'status',
        'pickup_time',
        'delivery_time',
        'distance_km',
        'price',
        'pickup_latitude',
        'pickup_longitude',
        'pickup_address',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_address',
        'estimated_duration_minutes',
        'proof_of_delivery',
        'delivered_at',
    ];

    protected $casts = [
        'pickup_time' => 'datetime',
        'delivery_time' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
