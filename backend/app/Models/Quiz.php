<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content_id',
        'author_id',
        'type',
        'time_limit',
        'passing_score',
        'max_attempts',
        'instructions',
        'randomize_questions',
        'show_correct_answers',
        'show_explanations',
        'allow_review',
        'is_published',
        'published_at',
        'question_count',
        'total_points',
        'attempt_count',
        'average_score',
        'metadata',
    ];

    protected $casts = [
        'instructions' => 'array',
        'randomize_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
        'show_explanations' => 'boolean',
        'allow_review' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'average_score' => 'decimal:2',
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

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(QuizCertificate::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('attempt_count', 'desc');
    }

    public function scopeRated($query)
    {
        return $query->orderBy('average_score', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    public function getDurationFormattedAttribute(): string
    {
        if (!$this->time_limit) {
            return 'No time limit';
        }
        
        $hours = floor($this->time_limit / 60);
        $minutes = $this->time_limit % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    public function getPassRateAttribute(): float
    {
        if ($this->attempt_count === 0) {
            return 0;
        }
        
        $passedAttempts = $this->attempts()->where('is_passed', true)->count();
        return round(($passedAttempts / $this->attempt_count) * 100, 2);
    }

    public function incrementAttemptCount(): void
    {
        $this->increment('attempt_count');
    }

    public function updateAverageScore(): void
    {
        $averageScore = $this->attempts()->where('status', 'completed')->avg('percentage');
        $this->update(['average_score' => $averageScore]);
    }

    public function canUserAttempt($userId): bool
    {
        if (!$this->is_published) {
            return false;
        }
        
        if ($this->max_attempts) {
            $userAttempts = $this->attempts()->where('user_id', $userId)->count();
            return $userAttempts < $this->max_attempts;
        }
        
        return true;
    }
}