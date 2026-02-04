<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'agency_id',
        'client_id',
        'integration_connection_id',
        'external_order_id',
        'status',
        'customer_data',
        'financials'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    protected $casts = [
        'customer_data' => 'array',
        'financials' => 'array',
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