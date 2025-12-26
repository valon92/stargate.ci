<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;

    protected $table = 'content_videos';

    protected $fillable = [
        'content_id',
        'title',
        'description',
        'youtube_id',
        'youtube_url',
        'content_type',
        'likes_count',
        'comments_count',
        'shares_count',
        'views_count',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'views_count' => 'integer'
    ];

    /**
     * Get the interactions for this video
     */
    public function interactions(): HasMany
    {
        return $this->hasMany(VideoInteraction::class, 'video_content_id', 'content_id');
    }

    /**
     * Get the comments for this video
     */
    public function comments(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'video_content_id', 'content_id')
                    ->where('is_active', true)
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Get the likes for this video
     */
    public function likes(): HasMany
    {
        return $this->interactions()->where('interaction_type', 'like');
    }

    /**
     * Get the shares for this video
     */
    public function shares(): HasMany
    {
        return $this->interactions()->where('interaction_type', 'share');
    }

    /**
     * Get the views for this video
     */
    public function views(): HasMany
    {
        return $this->interactions()->where('interaction_type', 'view');
    }

    /**
     * Scope for active videos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific content type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('content_type', $type);
    }

    /**
     * Update interaction counts
     */
    public function updateCounts()
    {
        $this->update([
            'likes_count' => $this->likes()->count(),
            'comments_count' => $this->comments()->count(),
            'shares_count' => $this->shares()->count(),
            'views_count' => $this->views()->count()
        ]);
    }
}