<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProximityPlan extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'duration',
        'duration_type', // day, week, month, year
        'status',
        'min_distance',
        'max_distance',
        'min_price',
        'max_price',
    ];
}