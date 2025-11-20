<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;
use Carbon\Carbon;

class EventNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public string $eventUrl;
    public string $subscriberEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, string $subscriberEmail)
    {
        $this->event = $event;
        $this->eventUrl = config('app.frontend_url') . '/events';
        $this->subscriberEmail = $subscriberEmail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 New Event: ' . $this->event->title . ' - Stargate.CI',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-notification',
            with: [
                'event' => $this->event,
                'eventUrl' => $this->eventUrl,
                'eventDate' => $this->getEventDate(),
                'eventTime' => $this->getEventTime(),
                'subscriberEmail' => $this->subscriberEmail,
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
     * Get formatted event date
     */
    private function getEventDate(): string
    {
        return $this->event->event_date->format('l, F j, Y');
    }

    /**
     * Get formatted event time
     */
    private function getEventTime(): ?string
    {
        if (!$this->event->event_time) {
            return null;
        }

        try {
            $timeString = $this->event->getRawOriginal('event_time');
            if (!$timeString) {
                return null;
            }

            $dateTime = Carbon::parse($this->event->event_date->format('Y-m-d') . ' ' . $timeString);
            return $dateTime->format('g:i A');
        } catch (\Exception $e) {
            return null;
        }
    }
}
