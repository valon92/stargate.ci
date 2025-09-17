<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerification extends Model
{
    use HasFactory;

    protected $table = 'email_verifications';

    protected $fillable = [
        'user_id',
        'email',
        'token',
        'expires_at',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_verified', false)
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function isExpired(): bool
    {
        return $this->expires_at <= now();
    }

    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    public function verify(): void
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);
    }

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->update([
            'token' => $token,
            'expires_at' => now()->addHours(24),
        ]);
        return $token;
    }
}