<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CommunityLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'user_id', // For backward compatibility
        'likeable_id',
        'likeable_type'
    ];

    /**
     * Get the subscriber who liked
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Get the parent likeable model (post or comment)
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Boot method to handle column mapping
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($like) {
            // Map subscriber_id to user_id if needed
            if (!$like->user_id && $like->subscriber_id) {
                $subscriber = Subscriber::find($like->subscriber_id);
                if ($subscriber) {
                    $user = \App\Models\User::where('email', $subscriber->email)->first();
                    if ($user) {
                        $like->user_id = $user->id;
                    }
                }
            }
            
            // Map user_id to subscriber_id if needed
            if (!$like->subscriber_id && $like->user_id) {
                $user = \App\Models\User::find($like->user_id);
                if ($user) {
                    $subscriber = Subscriber::where('email', $user->email)->first();
                    if ($subscriber) {
                        $like->subscriber_id = $subscriber->id;
                    }
                }
            }
        });
    }
}
