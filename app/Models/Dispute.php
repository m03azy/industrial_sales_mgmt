<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SalesOrder;
use App\Models\User;
use App\Models\Factory;

class Dispute extends Model
{
    protected $fillable = [
        'order_id',
        'retailer_id',
        'factory_id',
        'reason',
        'description',
        'status',
        'resolution',
    ];

    public function order()
    {
        return $this->belongsTo(SalesOrder::class, 'order_id');
    }

    public function retailer()
    {
        return $this->belongsTo(User::class, 'retailer_id');
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
}
