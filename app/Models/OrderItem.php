<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'external_item_id',
        'sku',
        'name',
        'quantity',
        'price',
        'tax',
        'shipping_charge',
        'tracking_number',
        'carrier',
        'raw',
    ];

    protected $casts = [
        'raw' => 'array',
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
