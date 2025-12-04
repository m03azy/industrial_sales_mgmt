<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoryProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'factory_id',
        'logo',
        'description',
        'operating_hours',
        'brand_color',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
}
