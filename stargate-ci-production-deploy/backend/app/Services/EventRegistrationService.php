<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Mail\EventReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventRegistrationService
{
    /**
     * Register a user for an event
     */
    public function registerForEvent(int $eventId, array $userData): EventRegistration
    {
        $event = Event::findOrFail($eventId);
        
        // Check if user is already registered
        $existingRegistration = EventRegistration::where('event_id', $eventId)
            ->where('email', $userData['email'])
            ->first();
            
        if ($existingRegistration) {
            throw new \Exception('You are already registered for this event.');
        }
        
        // Create new registration
        $registration = EventRegistration::create([
            'event_id' => $eventId,
            'email' => $userData['email'],
            'name' => $userData['name'] ?? null,
            'phone' => $userData['phone'] ?? null,
            'status' => 'registered',
            'preferences' => $userData['preferences'] ?? [],
            'registered_at' => Carbon::now()
        ]);
        
        Log::info('User registered for event', [
            'event_id' => $eventId,
            'email' => $userData['email'],
            'registration_id' => $registration->id
        ]);
        
        return $registration;
    }
    
    /**
     * Send reminder emails for upcoming events
     */
    public function sendEventReminders(): int
    {
        $registrations = EventRegistration::needsReminder()->get();
        $sentCount = 0;
        
        foreach ($registrations as $registration) {
            try {
                $this->sendReminderEmail($registration);
                $registration->markReminderSent();
                $sentCount++;
                
                Log::info('Event reminder sent', [
                    'registration_id' => $registration->id,
                    'event_id' => $registration->event_id,
                    'email' => $registration->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send event reminder', [
                    'registration_id' => $registration->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $sentCount;
    }
    
    /**
     * Send reminder email for a specific registration
     */
    public function sendReminderEmail(EventRegistration $registration): void
    {
        Mail::to($registration->email)->send(
            new EventReminderMail($registration->event, $registration)
        );
    }
    
    /**
     * Get registration statistics for an event
     */
    public function getEventStats(int $eventId): array
    {
        $event = Event::findOrFail($eventId);
        
        return [
            'total_registrations' => $event->registrations()->count(),
            'confirmed_registrations' => $event->registrations()->confirmed()->count(),
            'pending_registrations' => $event->registrations()->pending()->count(),
            'reminders_sent' => $event->registrations()->whereNotNull('reminder_sent_at')->count(),
            'event' => $event
        ];
    }
    
    /**
     * Cancel a registration
     */
    public function cancelRegistration(int $registrationId): bool
    {
        $registration = EventRegistration::findOrFail($registrationId);
        
        $registration->update(['status' => 'cancelled']);
        
        Log::info('Registration cancelled', [
            'registration_id' => $registrationId,
            'event_id' => $registration->event_id,
            'email' => $registration->email
        ]);
        
        return true;
    }
    
    /**
     * Get upcoming events for a user
     */
    public function getUserUpcomingEvents(string $email): array
    {
        return EventRegistration::where('email', $email)
            ->where('status', 'registered')
            ->whereHas('event', function($query) {
                $query->where('event_date', '>=', Carbon::today());
            })
            ->with('event')
            ->get()
            ->map(function($registration) {
                return [
                    'registration' => $registration,
                    'event' => $registration->event,
                    'days_until_event' => Carbon::parse($registration->event->event_date)->diffInDays(Carbon::today())
                ];
            })
            ->toArray();
    }
    
    /**
     * Schedule reminder emails (to be called by cron job)
     */
    public function scheduleReminders(): void
    {
        // Send reminders for events happening tomorrow
        $tomorrowRegistrations = EventRegistration::where('status', 'registered')
            ->whereNull('reminder_sent_at')
            ->whereHas('event', function($query) {
                $query->where('event_date', Carbon::tomorrow());
            })
            ->get();
            
        foreach ($tomorrowRegistrations as $registration) {
            $this->sendReminderEmail($registration);
            $registration->markReminderSent();
        }
        
        // Send reminders for events happening in 1 hour (if event has time)
        $oneHourRegistrations = EventRegistration::where('status', 'registered')
            ->whereNull('reminder_sent_at')
            ->whereHas('event', function($query) {
                $query->where('event_date', Carbon::today())
                      ->whereNotNull('event_time')
                      ->where('event_time', '<=', Carbon::now()->addHour())
                      ->where('event_time', '>', Carbon::now());
            })
            ->get();
            
        foreach ($oneHourRegistrations as $registration) {
            $this->sendReminderEmail($registration);
            $registration->markReminderSent();
        }
    }
}