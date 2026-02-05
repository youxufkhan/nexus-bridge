<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformProductMap extends Model
{
    protected $fillable = ['agency_id', 'product_id', 'integration_connection_id', 'external_id', 'external_sku'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function integrationConnection()
    {
        return $this->belongsTo(IntegrationConnection::class);
    }
    //
}
