<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RefreshToken extends Model
{
    use HasFactory;

    protected $table = 'refresh_tokens';

    // الحقول اللي مسموح mass assignment عليها
    protected $fillable = [
        'client_id',
        'access_jti',
        'refresh_token',
        'device_name',
        'device_id',
        'expires_at',
        'revoked',
    ];

    // casts لتسهيل التعامل
    protected $casts = [
        'expires_at' => 'datetime',
        'revoked' => 'boolean',
    ];

    /**
     * العلاقة مع العميل
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * تحقق هل التوكن منتهي
     */
    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * تحقق هل التوكن مفعّل
     */
    public function isActive(): bool
    {
        return !$this->revoked && !$this->isExpired();
    }
}
