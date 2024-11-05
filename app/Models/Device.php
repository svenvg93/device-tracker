<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'device_type',
        'network',
        'week_number',
        'amount',
    ];

    public function amounts()
    {
        return $this->hasMany(DeviceAmount::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
