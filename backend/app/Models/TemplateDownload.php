<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateDownload extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'user_id',
        'ip_address',
        'user_agent',
        'download_token',
        'metadata',
        'downloaded_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'downloaded_at' => 'datetime',
    ];

    /**
     * Get the template that was downloaded
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Get the user who downloaded (if authenticated)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for downloads by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('downloaded_at', [$startDate, $endDate]);
    }

    /**
     * Scope for authenticated user downloads
     */
    public function scopeAuthenticated($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope for anonymous downloads
     */
    public function scopeAnonymous($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Generate unique download token
     */
    public static function generateDownloadToken()
    {
        return 'dl_' . bin2hex(random_bytes(16));
    }

    /**
     * Get download source (authenticated or anonymous)
     */
    public function getSourceAttribute()
    {
        return $this->user_id ? 'authenticated' : 'anonymous';
    }

    /**
     * Get formatted download date
     */
    public function getFormattedDateAttribute()
    {
        return $this->downloaded_at->format('M j, Y g:i A');
    }
}