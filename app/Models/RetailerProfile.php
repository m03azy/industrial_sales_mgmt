<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'business_type',
        'preferred_delivery_method',
        'delivery_address',
        'delivery_instructions',
        'preferences',
    ];

    protected $casts = [
        'preferences' => 'array',
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
