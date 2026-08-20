<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventPassPurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public EventRegistration $registration;
    public ?User $user;
    public array $passes;
    public int $personCount;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, EventRegistration $registration, ?User $user = null, array $passes = [], int $personCount = 1)
    {
        $this->event = $event;
        $this->registration = $registration;
        $this->user = $user;
        $this->passes = $passes;
        $this->personCount = $personCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ Your Event Entry Pass(es) - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event_pass',
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
}
