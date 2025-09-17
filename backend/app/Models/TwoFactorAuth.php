<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorAuth extends Model
{
    use HasFactory;

    protected $table = 'two_factor_auth';

    protected $fillable = [
        'user_id',
        'secret_key',
        'qr_code',
        'backup_codes',
        'is_enabled',
        'enabled_at',
        'last_used_at',
    ];

    protected $casts = [
        'backup_codes' => 'array',
        'is_enabled' => 'boolean',
        'enabled_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function enable(): void
    {
        $this->update([
            'is_enabled' => true,
            'enabled_at' => now(),
        ]);
    }

    public function disable(): void
    {
        $this->update([
            'is_enabled' => false,
            'enabled_at' => null,
        ]);
    }

    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    public function hasValidBackupCode($code): bool
    {
        $backupCodes = $this->backup_codes ?? [];
        return in_array($code, $backupCodes);
    }

    public function removeBackupCode($code): void
    {
        $backupCodes = $this->backup_codes ?? [];
        $backupCodes = array_filter($backupCodes, fn($c) => $c !== $code);
        $this->update(['backup_codes' => array_values($backupCodes)]);
    }
}