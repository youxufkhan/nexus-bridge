<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['agency_id', 'client_id', 'master_sku', 'name', 'dimensions', 'weight'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    protected $casts = [
        'dimensions' => 'array',
        'weight' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function platformProductMaps()
    {
        return $this->hasMany(PlatformProductMap::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
//
}