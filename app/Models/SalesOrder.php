<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Retailer;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'order_date',
        'status',
        'total_amount',
        'sales_agent_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Retailer::class, 'customer_id'); // Keeping foreign key as customer_id for now if not renamed
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'customer_id');
    }

    public function salesAgent()
    {
        return $this->belongsTo(User::class, 'sales_agent_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
