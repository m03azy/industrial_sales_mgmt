<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reference',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    //
}
