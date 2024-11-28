<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    use HasFactory;

    protected $table = 'type';

    protected $fillable = [
        'type', // The name of the network (e.g., 'B2C', 'B2B', 'Mobile')
    ];

    protected $hidden = [];

    protected $casts = [];
}
