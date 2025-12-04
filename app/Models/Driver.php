<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Delivery;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'license_number',
        'is_available',
        'current_location',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
