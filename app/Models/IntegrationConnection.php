<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IntegrationConnection extends Model
{
    use HasFactory;

    protected $fillable = ['agency_id', 'client_id', 'platform_type', 'credentials', 'settings'];

    protected $casts = [
        'credentials' => 'array', // Use 'encrypted:array' in production for secrets
        'settings' => 'array',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function platformProductMaps()
    {
        return $this->hasMany(PlatformProductMap::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}