<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\Subscriber;
use App\Mail\EventNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        // Only send notifications for future events
        if ($event->event_date < now()) {
            return;
        }

        // Only send notifications for active events
        if (!$event->is_active) {
            return;
        }

        try {
            // Get all active subscribers with email notifications enabled
            $subscribers = Subscriber::where('is_active', true)
                ->where('email_notifications', true)
                ->whereNotNull('email_verified_at')
                ->get();

            if ($subscribers->isEmpty()) {
                Log::info('No active subscribers found for event notification', [
                    'event_id' => $event->id,
                    'event_title' => $event->title
                ]);
                return;
            }

            $sentCount = 0;
            foreach ($subscribers as $subscriber) {
                try {
                    Mail::to($subscriber->email)->send(
                        new EventNotificationMail($event, $subscriber->email)
                    );
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send event notification email', [
                        'event_id' => $event->id,
                        'subscriber_id' => $subscriber->id,
                        'email' => $subscriber->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Event notifications sent', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'sent_count' => $sentCount,
                'total_subscribers' => $subscribers->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send event notifications', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Optional: Send notification if event becomes active after being inactive
        if ($event->wasChanged('is_active') && $event->is_active) {
            // Only send if event is in the future
            if ($event->event_date >= now()) {
                $this->created($event);
            }
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
