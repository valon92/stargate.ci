<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'category',
        'difficulty',
        'estimated_time',
        'team_size',
        'budget_range',
        'features',
        'architecture',
        'implementation_steps',
        'requirements',
        'metadata',
        'version',
        'is_active',
        'is_featured',
        'download_count',
        'rating',
        'review_count',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'features' => 'array',
        'implementation_steps' => 'array',
        'requirements' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'download_count' => 'integer',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    /**
     * Get the user who created the template
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the template
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all reviews for this template
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(TemplateReview::class);
    }

    /**
     * Get all downloads for this template
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(TemplateDownload::class);
    }

    /**
     * Get users who downloaded this template
     */
    public function downloaders(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, TemplateDownload::class, 'template_id', 'id', 'id', 'user_id');
    }

    /**
     * Scope for active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured templates
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for templates by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for templates by difficulty
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Scope for popular templates (by download count)
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('download_count', 'desc')->limit($limit);
    }

    /**
     * Scope for highly rated templates
     */
    public function scopeHighlyRated($query, $minRating = 4.0)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Update rating and review count when reviews change
     */
    public function updateRatingStats()
    {
        $reviews = $this->reviews();
        $this->update([
            'rating' => $reviews->avg('rating') ?? 0,
            'review_count' => $reviews->count(),
        ]);
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Get formatted rating
     */
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    /**
     * Get difficulty badge color
     */
    public function getDifficultyColorAttribute()
    {
        return match($this->difficulty) {
            'beginner' => 'green',
            'intermediate' => 'yellow',
            'advanced' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get category icon
     */
    public function getCategoryIconAttribute()
    {
        return match($this->category) {
            'AI & Machine Learning' => '🤖',
            'Data Analytics' => '📊',
            'Cloud Infrastructure' => '☁️',
            'Enterprise Solutions' => '🏢',
            'Startup Solutions' => '🚀',
            'IoT & Edge Computing' => '🌐',
            default => '📋'
        };
    }
}