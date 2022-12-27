<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessCategory extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

    public function bussiness_profile() : HasMany
    {
        return $this->hasMany(BusinessProfile::class);
    }
}
