<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company',
        'company_size',
        'industry',
        'investment_capacity',
        'interests',
        'location',
        'phone',
        'website',
        'message',
        'preferred_contact',
        'avatar',
        'bio',
        'social_links',
        'email_notifications',
        'sms_notifications',
        'preferences',
        'last_active_at',
    ];

    protected $casts = [
        'interests' => 'array',
        'social_links' => 'array',
        'preferences' => 'array',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByCompanySize($query, $size)
    {
        return $query->where('company_size', $size);
    }

    public function scopeByIndustry($query, $industry)
    {
        return $query->where('industry', $industry);
    }

    public function scopeByInvestmentCapacity($query, $capacity)
    {
        return $query->where('investment_capacity', $capacity);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('last_active_at')
                    ->where('last_active_at', '>', now()->subDays(30));
    }
}