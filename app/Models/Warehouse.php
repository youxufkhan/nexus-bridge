<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory;

class Warehouse extends Model
{
    protected $fillable = ['agency_id', 'name', 'location_code', 'address'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    protected $casts = [
        'address' => 'array',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
//
}