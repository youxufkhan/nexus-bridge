<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['agency_id', 'name', 'code', 'is_active'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function integrationConnections()
    {
        return $this->hasMany(IntegrationConnection::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
