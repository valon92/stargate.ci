<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'user_id',
        'tags',
        'is_anonymous',
        'status',
        'view_count',
        'like_count',
        'comment_count',
        'moderation_notes',
        'moderated_by',
        'moderated_at',
        'featured_at',
        'pinned',
        'metadata'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_anonymous' => 'boolean',
        'pinned' => 'boolean',
        'moderated_at' => 'datetime',
        'featured_at' => 'datetime',
        'metadata' => 'array'
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CommunityCategory::class, 'category_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommunityComment::class, 'post_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommunityLike::class, 'post_id');
    }

    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
