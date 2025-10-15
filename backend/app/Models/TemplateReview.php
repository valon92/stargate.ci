<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'user_id',
        'rating',
        'review',
        'pros',
        'cons',
        'is_verified_download',
        'is_helpful',
        'helpful_count',
    ];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'is_verified_download' => 'boolean',
        'is_helpful' => 'boolean',
        'helpful_count' => 'integer',
    ];

    /**
     * Get the template this review belongs to
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the user who wrote this review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for verified downloads
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified_download', true);
    }

    /**
     * Scope for helpful reviews
     */
    public function scopeHelpful($query)
    {
        return $query->where('is_helpful', true);
    }

    /**
     * Scope for high ratings
     */
    public function scopeHighRating($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Get formatted rating with stars
     */
    public function getFormattedRatingAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get review summary (first 100 characters)
     */
    public function getSummaryAttribute()
    {
        return strlen($this->review) > 100 
            ? substr($this->review, 0, 100) . '...' 
            : $this->review;
    }
}