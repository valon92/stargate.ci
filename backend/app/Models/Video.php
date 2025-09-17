<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content_id',
        'author_id',
        'video_url',
        'thumbnail_url',
        'poster_url',
        'duration',
        'format',
        'file_size',
        'quality_options',
        'subtitles',
        'chapters',
        'has_transcript',
        'transcript',
        'is_public',
        'allow_download',
        'allow_embed',
        'view_count',
        'like_count',
        'dislike_count',
        'average_rating',
        'metadata',
    ];

    protected $casts = [
        'quality_options' => 'array',
        'subtitles' => 'array',
        'chapters' => 'array',
        'has_transcript' => 'boolean',
        'is_public' => 'boolean',
        'allow_download' => 'boolean',
        'allow_embed' => 'boolean',
        'average_rating' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function contentItem(): BelongsTo
    {
        return $this->belongsTo(ContentItem::class, 'content_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(VideoAnalytics::class);
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(VideoPlaylist::class, 'video_playlist_items', 'video_id', 'playlist_id')
                    ->withPivot(['sort_order', 'added_at'])
                    ->withTimestamps();
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByFormat($query, $format)
    {
        return $query->where('format', $format);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    public function scopeRated($query)
    {
        return $query->orderBy('average_rating', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getDurationFormattedAttribute(): string
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return sprintf('%d:%02d', $minutes, $seconds);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementLikeCount(): void
    {
        $this->increment('like_count');
    }

    public function incrementDislikeCount(): void
    {
        $this->increment('dislike_count');
    }

    public function updateAverageRating(): void
    {
        // This would typically calculate from user ratings
        // For now, we'll leave it as is
    }
}