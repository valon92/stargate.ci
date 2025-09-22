<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'path',
        'url',
        'mime_type',
        'size',
        'type',
        'context',
        'context_id',
        'metadata',
        'is_processed',
        'processed_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime'
    ];

    protected $appends = ['full_url', 'human_size'];

    /**
     * Get the user that owns the media file
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full URL to the file
     */
    public function getFullUrlAttribute(): string
    {
        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        }
        
        return Storage::url($this->path);
    }

    /**
     * Get human readable file size
     */
    public function getHumanSizeAttribute(): string
    {
        $size = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Check if file is an image
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Check if file is a video
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Check if file is an audio file
     */
    public function isAudio(): bool
    {
        return $this->type === 'audio';
    }

    /**
     * Check if file is a document
     */
    public function isDocument(): bool
    {
        return $this->type === 'document';
    }

    /**
     * Get thumbnail URL for images
     */
    public function getThumbnailUrl(string $size = 'medium'): ?string
    {
        if (!$this->isImage() || !$this->metadata) {
            return null;
        }

        $thumbnails = $this->metadata['thumbnails'] ?? [];
        return $thumbnails[$size]['url'] ?? $this->full_url;
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by context
     */
    public function scopeInContext($query, string $context, $contextId = null)
    {
        $query = $query->where('context', $context);
        
        if ($contextId !== null) {
            $query->where('context_id', $contextId);
        }
        
        return $query;
    }

    /**
     * Scope to filter by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}