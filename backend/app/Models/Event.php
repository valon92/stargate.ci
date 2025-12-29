<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Subscriber;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'title',
        'description',
        'category',
        'type',
        'event_date',
        'event_time',
        'location',
        'organizer',
        'organizer_id',
        'icon',
        'registration_url',
        'video_url',
        'source',
        'metadata',
        'is_featured',
        'is_active',
        'last_synced_at'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i:s',
        'metadata' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'last_synced_at' => 'datetime'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', Carbon::today());
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('M d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        return $this->event_time ? $this->event_time->format('g:i A') : null;
    }

    public function getIsUpcomingAttribute()
    {
        return $this->event_date >= Carbon::today();
    }

    // Relationships
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function organizerUser(): BelongsTo
    {
        return $this->belongsTo(Subscriber::class, 'organizer_id');
    }

    // Methods
    public function syncFromExternalApi($data, $source)
    {
        $this->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => $data['category'],
            'type' => $data['type'],
            'event_date' => $data['event_date'],
            'event_time' => $data['event_time'] ?? null,
            'location' => $data['location'],
            'organizer' => $data['organizer'],
            'icon' => $data['icon'] ?? '📅',
            'registration_url' => $data['registration_url'] ?? null,
            'video_url' => $data['video_url'] ?? null,
            'source' => $source,
            'metadata' => $data['metadata'] ?? null,
            'last_synced_at' => now()
        ]);
    }
}
