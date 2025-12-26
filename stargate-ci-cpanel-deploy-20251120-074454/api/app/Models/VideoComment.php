<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_content_id',
        'subscriber_id',
        'session_id',
        'parent_id',
        'content',
        'likes_count',
        'is_pinned',
        'is_edited',
        'edited_at',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_pinned' => 'boolean',
        'is_edited' => 'boolean',
        'is_active' => 'boolean',
        'likes_count' => 'integer',
        'edited_at' => 'datetime'
    ];

    /**
     * Get the video that owns this comment
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_content_id', 'content_id');
    }

    /**
     * Get the subscriber that owns this comment
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the parent comment
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(VideoComment::class, 'parent_id');
    }

    /**
     * Get the replies for this comment
     */
    public function replies(): HasMany
    {
        return $this->hasMany(VideoComment::class, 'parent_id')
                    ->where('is_active', true)
                    ->orderBy('created_at', 'asc');
    }

    /**
     * Get the likes for this comment
     */
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * Scope for active comments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for main comments (not replies)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for replies
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope for pinned comments
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Get display name for comment author
     */
    public function getAuthorNameAttribute()
    {
        if ($this->subscriber) {
            return $this->subscriber->display_name;
        }
        return 'Guest User';
    }

    /**
     * Get avatar initial for comment author
     */
    public function getAuthorAvatarAttribute()
    {
        if ($this->subscriber) {
            return $this->subscriber->avatar_initial;
        }
        return 'G';
    }

    /**
     * Update likes count
     */
    public function updateLikesCount()
    {
        $this->update(['likes_count' => $this->likes()->count()]);
    }

    /**
     * Mark as edited
     */
    public function markAsEdited()
    {
        $this->update([
            'is_edited' => true,
            'edited_at' => now()
        ]);
    }

    /**
     * Toggle pin status
     */
    public function togglePin()
    {
        $this->update(['is_pinned' => !$this->is_pinned]);
        return $this->is_pinned;
    }
}
