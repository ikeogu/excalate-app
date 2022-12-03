<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Maatwebsite\Excel\Concerns\WithLimit;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'email',
        'password',
        'last_name',
        'phone_number',
        'address',
        'gender',
        'city',
        'state',
        'longitude',
        'latitude',
        'avatar',
        'role',
        'status',
        'email_verified_at'
    ];

    protected $with = ['business_profile', 'contacts'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'is_verified',
        'profile_picture',
        'role'
    ];

    /** @codeCoverageIgnore */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
    }

    /** @codeCoverageIgnore */
    protected function profilePicture(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMediaUrl('avatar') ?: null
        );
    }

    /** @codeCoverageIgnore */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    /** @codeCoverageIgnore */
    public function isVerified(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->email_verified_at),
        );
    }

    /** @codeCoverageIgnore */
    public function role(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getRoleNames()[0] ?? null
        );
    }

    public function business_profile() : HasMany
    {
        return $this->hasMany(BusinessProfile::class);
    }

    public function business_category() : BelongsTo
    {
        return $this->belongsTo(BusinessCategory::class);
    }

    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class);
    }
}