<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    // العلاقات
    public function city()
    {
        return $this->belongsTo(Area::class, 'city_id');
    }

    public function governorate()
    {
        return $this->belongsTo(Area::class, 'governorate_id');
    }

    public function representative()
    {
        return $this->belongsTo(Representative::class, 'representative_id');
    }

    public function distributor()
    {
        return $this->belongsTo(Representative::class, 'distributor_id');
    }

    public function representatives()
    {
        return $this->belongsToMany(Representative::class, 'representative_clients');
    }

    public function subscription()
    {
        return $this->belongsTo(ClientSubscription::class, 'client_subscription_id');
    }

    // ===== JWTSubject Methods =====
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function refreshTokens()
{
    return $this->hasMany(RefreshToken::class);
}

}
