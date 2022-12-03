<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'lat',
        'long',
        'qualifications',
        'min_charge',
        'service_type',
        'user_id',
        'business_category_id',
        'rating',
        'status'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function business_category() : BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class);
    }
}
