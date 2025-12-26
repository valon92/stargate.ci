<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'email', 'name', 'phone', 'status', 
        'preferences', 'registered_at', 'reminder_sent_at', 'confirmed_at'
    ];

    protected $casts = [
        'preferences' => 'array',
        'registered_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'confirmed_at' => 'datetime'
    ];

    /**
     * Get the event that this registration belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Scope for confirmed registrations
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'registered');
    }

    /**
     * Scope for registrations that need reminders
     */
    public function scopeNeedsReminder($query)
    {
        return $query->where('status', 'registered')
                    ->whereNull('reminder_sent_at')
                    ->whereHas('event', function($q) {
                        $q->where('event_date', '>=', Carbon::today())
                          ->where('event_date', '<=', Carbon::today()->addDays(7));
                    });
    }

    /**
     * Check if registration is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if reminder has been sent
     */
    public function hasReminderBeenSent(): bool
    {
        return !is_null($this->reminder_sent_at);
    }

    /**
     * Mark reminder as sent
     */
    public function markReminderSent(): void
    {
        $this->update(['reminder_sent_at' => Carbon::now()]);
    }

    /**
     * Confirm registration
     */
    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => Carbon::now()
        ]);
    }
}