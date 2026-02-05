<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'client_id',
        'integration_connection_id',
        'external_order_id',
        'status',
        'total_amount',
        'total_tax',
        'total_shipping',
        'currency',
        'customer_data',
        'financials',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected $casts = [
        'customer_data' => 'array',
        'financials' => 'array',
        'total_amount' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total_shipping' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function integrationConnection()
    {
        return $this->belongsTo(IntegrationConnection::class);
    }
}
