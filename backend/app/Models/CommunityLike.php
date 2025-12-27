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
}
