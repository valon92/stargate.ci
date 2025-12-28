<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CommunityComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'community_post_id',
        'post_id', // For backward compatibility
        'subscriber_id',
        'user_id', // For backward compatibility
        'parent_id',
        'content',
        'likes_count',
        'like_count', // For backward compatibility
        'is_pinned',
        'status'
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'likes_count' => 'integer'
    ];

    /**
     * Get the post this comment belongs to
     */
    public function post(): BelongsTo
    {
        // Use community_post_id if available, otherwise fall back to post_id
        $foreignKey = $this->community_post_id ? 'community_post_id' : 'post_id';
        return $this->belongsTo(CommunityPost::class, $foreignKey);
    }
    
    /**
     * Boot method to handle column mapping
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($comment) {
            // Map community_post_id to post_id if needed
            if (!$comment->post_id && $comment->community_post_id) {
                $comment->post_id = $comment->community_post_id;
            }
            
            // Map post_id to community_post_id if needed
            if (!$comment->community_post_id && $comment->post_id) {
                $comment->community_post_id = $comment->post_id;
            }
            
            // Map subscriber_id to user_id if needed
            if (!$comment->user_id && $comment->subscriber_id) {
                $subscriber = Subscriber::find($comment->subscriber_id);
                if ($subscriber) {
                    $user = \App\Models\User::where('email', $subscriber->email)->first();
                    if ($user) {
                        $comment->user_id = $user->id;
                    }
                }
            }
            
            // Map user_id to subscriber_id if needed
            if (!$comment->subscriber_id && $comment->user_id) {
                $user = \App\Models\User::find($comment->user_id);
                if ($user) {
                    $subscriber = Subscriber::where('email', $user->email)->first();
                    if ($subscriber) {
                        $comment->subscriber_id = $subscriber->id;
                    }
                }
            }
        });
    }
    
    /**
     * Accessor for likes_count (handles both like_count and likes_count)
     */
    public function getLikesCountAttribute($value)
    {
        return $value ?? $this->attributes['like_count'] ?? 0;
    }

    /**
     * Get the subscriber (author) of the comment
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the parent comment (if this is a reply)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CommunityComment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies(): HasMany
    {
        return $this->hasMany(CommunityComment::class, 'parent_id')->where('status', 'published')->orderBy('created_at', 'asc');
    }

    /**
     * Get all likes for this comment
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(CommunityLike::class, 'likeable');
    }

    /**
     * Check if a subscriber has liked this comment
     */
    public function isLikedBy(?int $subscriberId): bool
    {
        if (!$subscriberId) {
            return false;
        }
        return $this->likes()->where('subscriber_id', $subscriberId)->exists();
    }

    /**
     * Scope for published comments
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
