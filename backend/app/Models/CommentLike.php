<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'subscriber_id',
        'session_id',
        'liked_at'
    ];

    protected $casts = [
        'liked_at' => 'datetime'
    ];

    /**
     * Get the comment that owns this like
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(VideoComment::class);
    }

    /**
     * Get the subscriber that owns this like
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Scope for recent likes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('liked_at', '>=', now()->subDays($days));
    }

    /**
     * Check if comment is liked
     */
    public static function isLiked($commentId, $subscriberId = null, $sessionId = null)
    {
        $query = static::where('comment_id', $commentId);

        if ($subscriberId) {
            $query->where('subscriber_id', $subscriberId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->exists();
    }

    /**
     * Toggle like status
     */
    public static function toggle($commentId, $subscriberId = null, $sessionId = null)
    {
        $query = static::where('comment_id', $commentId);

        if ($subscriberId) {
            $query->where('subscriber_id', $subscriberId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        $like = $query->first();

        if ($like) {
            $like->delete();
            return false; // Unliked
        } else {
            static::create([
                'comment_id' => $commentId,
                'subscriber_id' => $subscriberId,
                'session_id' => $sessionId,
                'liked_at' => now()
            ]);
            return true; // Liked
        }
    }
}
