<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Retailer;

class SalesOrder extends Model
{
    protected $fillable = [
        'customer_id',
        'retailer_id',
        'user_id',
        'order_number',
        'invoice_number',
        'order_date',
        'status',
        'total_amount',
        'subtotal',
        'tax',
        'sales_agent_id',
        'delivery_address',
        'delivery_notes',
        'payment_method',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
