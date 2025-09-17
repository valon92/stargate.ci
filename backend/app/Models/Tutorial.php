<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tutorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'overview',
        'content_id',
        'author_id',
        'estimated_duration',
        'difficulty_level',
        'learning_objectives',
        'prerequisites',
        'resources',
        'thumbnail',
        'steps',
        'is_interactive',
        'has_quiz',
        'has_certificate',
        'is_published',
        'published_at',
        'completion_count',
        'average_rating',
        'metadata',
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'prerequisites' => 'array',
        'resources' => 'array',
        'steps' => 'array',
        'is_interactive' => 'boolean',
        'has_quiz' => 'boolean',
        'has_certificate' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
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

    public function steps(): HasMany
    {
        return $this->hasMany(TutorialStep::class);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserTutorialProgress::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByDifficulty($query, $level)
    {
        return $query->where('difficulty_level', $level);
    }

    public function scopeInteractive($query)
    {
        return $query->where('is_interactive', true);
    }

    public function scopeWithQuiz($query)
    {
        return $query->where('has_quiz', true);
    }

    public function scopeWithCertificate($query)
    {
        return $query->where('has_certificate', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('completion_count', 'desc');
    }

    public function scopeRated($query)
    {
        return $query->orderBy('average_rating', 'desc');
    }

    public function getDurationFormattedAttribute(): string
    {
        $hours = floor($this->estimated_duration / 60);
        $minutes = $this->estimated_duration % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    public function getStepCountAttribute(): int
    {
        return $this->steps()->count();
    }

    public function incrementCompletionCount(): void
    {
        $this->increment('completion_count');
    }

    public function updateAverageRating(): void
    {
        // This would typically calculate from user ratings
        // For now, we'll leave it as is
    }
}