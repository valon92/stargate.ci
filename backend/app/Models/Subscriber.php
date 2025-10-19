<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'country',
        'profession',
        'company',
        'interests',
        'avatar',
        'is_active',
        'email_notifications',
        'subscribed_at',
        'last_activity_at',
        'preferences'
    ];

    protected $casts = [
        'interests' => 'array',
        'preferences' => 'array',
        'is_active' => 'boolean',
        'email_notifications' => 'boolean',
        'subscribed_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the interactions for this subscriber
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(VideoInteraction::class);
    }

    /**
     * Get the comments for this subscriber
     */
    public function comments(): HasMany
    {
        return $this->hasMany(VideoComment::class);
    }

    /**
     * Get the comment likes for this subscriber
     */
    public function commentLikes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Scope for active subscribers
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        return $this->username;
    }

    /**
     * Get avatar initial
     */
    public function getAvatarInitialAttribute()
    {
        if ($this->avatar && !str_contains($this->avatar, 'http')) {
            return $this->avatar;
        }
        
        return strtoupper(substr($this->display_name, 0, 1));
    }

    /**
     * Update last activity
     */
    public function updateLastActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }
}
