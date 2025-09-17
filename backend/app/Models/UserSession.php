<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'is_active',
        'last_activity',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_activity' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('last_activity', '>', now()->subHours($hours));
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function isActive(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function activate(): void
    {
        $this->update([
            'is_active' => true,
            'last_activity' => now(),
        ]);
    }

    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    public function updateActivity(): void
    {
        $this->update(['last_activity' => now()]);
    }

    public function extend($hours = 24): void
    {
        $this->update(['expires_at' => now()->addHours($hours)]);
    }
}