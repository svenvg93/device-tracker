<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceCounter extends Model
{
    protected $fillable = [
        'name',
        'device_type',
        'network',
        'amount',
    ];

    public function amounts()
    {
        return $this->hasMany(DeviceAmount::class);
    }
}
