<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCounter extends Model
{
    protected $fillable = [
        'device_name',
        'device_type',
        'device_network',
        'device_amount',
        'current_date',
    ];

    public function amounts()
    {
        return $this->hasMany(DeviceAmount::class);
    }
}
