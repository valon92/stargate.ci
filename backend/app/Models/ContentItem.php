<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'type',
        'category_id',
        'author_id',
        'featured_image',
        'media_files',
        'read_time',
        'difficulty_level',
        'prerequisites',
        'learning_objectives',
        'tags',
        'is_published',
        'published_at',
        'view_count',
        'like_count',
        'comment_count',
        'rating',
        'metadata',
    ];

    protected $casts = [
        'media_files' => 'array',
        'prerequisites' => 'array',
        'learning_objectives' => 'array',
        'tags' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'rating' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ContentCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ContentVersion::class, 'content_id');
    }

    public function tutorial(): HasOne
    {
        return $this->hasOne(Tutorial::class, 'content_id');
    }

    public function video(): HasOne
    {
        return $this->hasOne(Video::class, 'content_id');
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class, 'content_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    public function scopeRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function incrementLikeCount(): void
    {
        $this->increment('like_count');
    }

    public function decrementLikeCount(): void
    {
        $this->decrement('like_count');
    }

    public function updateRating(): void
    {
        // This would typically calculate from user ratings
        // For now, we'll leave it as is
    }
}