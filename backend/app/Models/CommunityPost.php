<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'user_id', // For backward compatibility
        'title',
        'slug',
        'content',
        'category',
        'category_id', // For backward compatibility
        'type', // Required by existing table structure
        'tags',
        'is_pinned',
        'is_locked',
        'views_count',
        'view_count', // For backward compatibility
        'likes_count',
        'like_count', // For backward compatibility
        'comments_count',
        'comment_count', // For backward compatibility
        'shares_count',
        'share_count', // For backward compatibility
        'status'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer'
    ];

    /**
     * Get the subscriber (author) of the post
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Boot method to handle subscriber_id from user_id and generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // If subscriber_id is not set but user_id is, try to find subscriber
            if (!$post->subscriber_id && isset($post->user_id) && $post->user_id) {
                $user = \App\Models\User::find($post->user_id);
                if ($user) {
                    $subscriber = Subscriber::where('email', $user->email)->first();
                    if ($subscriber) {
                        $post->subscriber_id = $subscriber->id;
                    }
                }
            }
            
            // Set user_id from subscriber_id if not set
            if (!$post->user_id && $post->subscriber_id) {
                $subscriber = Subscriber::find($post->subscriber_id);
                if ($subscriber) {
                    $user = \App\Models\User::where('email', $subscriber->email)->first();
                    if ($user) {
                        $post->user_id = $user->id;
                    }
                }
            }
            
            // Generate slug from title if not provided
            if (!$post->slug && $post->title) {
                $post->slug = \Illuminate\Support\Str::slug($post->title);
                // Ensure uniqueness
                $count = static::where('slug', $post->slug)->count();
                if ($count > 0) {
                    $post->slug = $post->slug . '-' . ($count + 1);
                }
            }
            
            // Set type if not provided
            if (!$post->type) {
                $post->type = 'post';
            }
        });
    }

    /**
     * Accessor for views_count (handles both view_count and views_count)
     */
    public function getViewsCountAttribute($value)
    {
        return $value ?? $this->attributes['view_count'] ?? 0;
    }

    /**
     * Accessor for likes_count (handles both like_count and likes_count)
     */
    public function getLikesCountAttribute($value)
    {
        return $value ?? $this->attributes['like_count'] ?? 0;
    }

    /**
     * Accessor for comments_count (handles both comment_count and comments_count)
     */
    public function getCommentsCountAttribute($value)
    {
        return $value ?? $this->attributes['comment_count'] ?? 0;
    }

    /**
     * Accessor for shares_count (handles both share_count and shares_count)
     */
    public function getSharesCountAttribute($value)
    {
        return $value ?? $this->attributes['share_count'] ?? 0;
    }

    /**
     * Accessor for category (handles both category and category_id)
     */
    public function getCategoryAttribute($value)
    {
        // If category column exists and has value, return it
        if (isset($this->attributes['category']) && $this->attributes['category']) {
            return $this->attributes['category'];
        }
        // If category_id exists, map it to category name
        if (isset($this->attributes['category_id'])) {
            // Map category_id to category name (you can customize this)
            $categoryMap = [
                1 => 'general',
                2 => 'experience',
                3 => 'question',
                4 => 'idea',
                5 => 'discussion'
            ];
            return $categoryMap[$this->attributes['category_id']] ?? 'general';
        }
        return 'general';
    }

    /**
     * Mutator for category - also set category_id if needed
     */
    public function setCategoryAttribute($value)
    {
        $this->attributes['category'] = $value;
        // Optionally map category to category_id if needed
        $categoryIdMap = [
            'general' => 1,
            'experience' => 2,
            'question' => 3,
            'idea' => 4,
            'discussion' => 5
        ];
        if (isset($categoryIdMap[$value])) {
            $this->attributes['category_id'] = $categoryIdMap[$value];
        }
    }

    /**
     * Get all comments for this post
     */
    public function comments(): HasMany
    {
        // Use community_post_id as foreign key (migrated from post_id)
        return $this->hasMany(CommunityComment::class, 'community_post_id', 'id')
            ->where('status', 'published')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get all likes for this post
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(CommunityLike::class, 'likeable');
    }

    /**
     * Check if a subscriber has liked this post
     */
    public function isLikedBy(?int $subscriberId): bool
    {
        if (!$subscriberId) {
            return false;
        }
        return $this->likes()->where('subscriber_id', $subscriberId)->exists();
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for pinned posts
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
