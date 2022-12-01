<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'location',
        'lat',
        'long',
        'qualifications',
        'min_charge',
        'service_type',
        'user_id',
        'busness_cat_id',

    ];

    public function users() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function busness_category() : BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class);
    }
}
