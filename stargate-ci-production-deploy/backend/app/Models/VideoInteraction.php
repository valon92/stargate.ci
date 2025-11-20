<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_content_id',
        'subscriber_id',
        'session_id',
        'interaction_type',
        'platform',
        'metadata',
        'interacted_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'interacted_at' => 'datetime'
    ];

    /**
     * Get the video that owns this interaction
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class, 'video_content_id', 'content_id');
    }

    /**
     * Get the subscriber that owns this interaction
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Scope for specific interaction type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('interaction_type', $type);
    }

    /**
     * Scope for specific platform
     */
    public function scopeOnPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }

    /**
     * Scope for recent interactions
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('interacted_at', '>=', now()->subDays($days));
    }

    /**
     * Check if interaction exists
     */
    public static function exists($videoContentId, $interactionType, $subscriberId = null, $sessionId = null)
    {
        $query = static::where('video_content_id', $videoContentId)
                       ->where('interaction_type', $interactionType);

        if ($subscriberId) {
            $query->where('subscriber_id', $subscriberId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        return $query->exists();
    }

    /**
     * Toggle interaction (like/unlike)
     */
    public static function toggle($videoContentId, $interactionType, $subscriberId = null, $sessionId = null, $platform = null)
    {
        $query = static::where('video_content_id', $videoContentId)
                       ->where('interaction_type', $interactionType);

        if ($subscriberId) {
            $query->where('subscriber_id', $subscriberId);
        } elseif ($sessionId) {
            $query->where('session_id', $sessionId);
        }

        $interaction = $query->first();

        if ($interaction) {
            $interaction->delete();
            return false; // Removed
        } else {
            static::create([
                'video_content_id' => $videoContentId,
                'subscriber_id' => $subscriberId,
                'session_id' => $sessionId,
                'interaction_type' => $interactionType,
                'platform' => $platform,
                'interacted_at' => now()
            ]);
            return true; // Added
        }
    }
}
