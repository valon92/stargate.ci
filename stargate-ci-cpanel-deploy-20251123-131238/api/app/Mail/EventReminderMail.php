<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;

class EventReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public EventRegistration $registration;
    public string $eventUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, EventRegistration $registration)
    {
        $this->event = $event;
        $this->registration = $registration;
        $this->eventUrl = config('app.frontend_url') . '/events';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🚀 Event Reminder: ' . $this->event->title . ' - Tomorrow!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-reminder',
            with: [
                'event' => $this->event,
                'registration' => $this->registration,
                'eventUrl' => $this->eventUrl,
                'eventDateTime' => $this->getEventDateTime(),
                'timeUntilEvent' => $this->getTimeUntilEvent(),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get formatted event date and time
     */
    private function getEventDateTime(): string
    {
        $date = $this->event->event_date->format('l, F j, Y');
        $time = $this->event->event_time 
            ? $this->event->getRawOriginal('event_time')
            : 'TBD';
        
        return $date . ' at ' . $time;
    }

    /**
     * Get time until event
     */
    private function getTimeUntilEvent(): string
    {
        $eventDate = $this->event->event_date->format('Y-m-d');
        
        if ($this->event->event_time) {
            $timeString = $this->event->getRawOriginal('event_time');
            $eventDateTime = Carbon::parse($eventDate . ' ' . $timeString);
        } else {
            $eventDateTime = Carbon::parse($eventDate);
        }
        
        $now = Carbon::now();
        
        if ($eventDateTime->isToday()) {
            return 'Today';
        } elseif ($eventDateTime->isTomorrow()) {
            return 'Tomorrow';
        } else {
            return $eventDateTime->diffForHumans();
        }
    }
}