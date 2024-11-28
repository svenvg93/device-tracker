<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    // The table associated with the model (optional if the table name is plural and follows Laravel conventions)
    protected $table = 'networks';

    // The attributes that are mass assignable
    protected $fillable = [
        'name', // The name of the network (e.g., 'B2C', 'B2B', 'Mobile')
    ];

    // The attributes that should be hidden for arrays (optional, if needed)
    protected $hidden = [];

    // The attributes that should be cast (e.g., dates)
    protected $casts = [];

    // You can define relationships or other methods here if necessary
}
