<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReputation extends Model
{
    use HasFactory;

    protected $table = 'user_reputation';

    protected $fillable = [
        'user_id',
        'points',
        'level',
        'title',
        'achievements',
        'last_updated_at',
    ];

    protected $casts = [
        'achievements' => 'array',
        'last_updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeTopContributors($query, $limit = 10)
    {
        return $query->orderBy('points', 'desc')->limit($limit);
    }

    public function addPoints($points): void
    {
        $this->increment('points', $points);
        $this->updateLevel();
        $this->update(['last_updated_at' => now()]);
    }

    public function updateLevel(): void
    {
        $newLevel = min(floor($this->points / 500) + 1, 10); // Max level 10
        if ($newLevel > $this->level) {
            $this->update(['level' => $newLevel]);
        }
    }

    public function getTitleAttribute($value): string
    {
        if (!$value) {
            return $this->getDefaultTitle();
        }
        return $value;
    }

    private function getDefaultTitle(): string
    {
        $titles = [
            1 => 'Newcomer',
            2 => 'Active Member',
            3 => 'Contributor',
            4 => 'Helper',
            5 => 'Expert',
            6 => 'Guru',
            7 => 'Master',
            8 => 'Legend',
            9 => 'Champion',
            10 => 'Stargate Hero'
        ];

        return $titles[$this->level] ?? 'Member';
    }
}