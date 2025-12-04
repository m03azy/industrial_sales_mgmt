<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'phone',
        'address',
        'user_id',
    ];

    public function profile()
    {
        return $this->hasOne(RetailerProfile::class);
    }

    public function orders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
