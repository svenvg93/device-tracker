<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_name', // Add this line to allow mass assignment for device_name
        'color', // Ensure this is also included if you have it
    ];
}
