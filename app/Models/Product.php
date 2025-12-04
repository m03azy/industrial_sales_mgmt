<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'factory_id',
        'sku',
        'name',
        'description',
        'cost_price',
        'selling_price',
        'stock_quantity',
        'low_stock_threshold',
        'category',
        'image'
    ];

    protected $appends = [];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
