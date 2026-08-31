<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\EventSponsor;
use App\Models\SponsorshipType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class SponsorshipReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Event $event;
    public EventSponsor $sponsor;
    public ?SponsorshipType $sponsorshipType;
    public string $receiptNo;
    public float $amount;
    public string $paymentStatus;
    public ?string $paymentId;

    /**
     * Create a new message instance.
     */
    public function __construct(Event $event, EventSponsor $sponsor, ?SponsorshipType $sponsorshipType = null, ?float $amount = null, ?string $paymentStatus = null, ?string $paymentId = null)
    {
        $this->event = $event;
        $this->sponsor = $sponsor;
        $this->sponsorshipType = $sponsorshipType ?? $sponsor->sponsorshipType;
        $this->amount = $amount ?? (float)($sponsor->amount ?? 0);
        $this->paymentStatus = $paymentStatus ?? ($sponsor->payment_status ?? 'pending');
        $this->paymentId = $paymentId ?? $sponsor->payment_id;
        $this->receiptNo = 'RCP-SPN-' . date('Y') . '-' . sprintf('%05d', $sponsor->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Sponsorship Receipt #' . $this->receiptNo . ' - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sponsorship_receipt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.receipt_pdf.sponsorship', [
            'event' => $this->event,
            'sponsor' => $this->sponsor,
            'sponsorshipType' => $this->sponsorshipType,
            'receiptNo' => $this->receiptNo,
            'amount' => $this->amount,
            'paymentStatus' => $this->paymentStatus,
            'paymentId' => $this->paymentId,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Sponsorship_Receipt_' . $this->receiptNo . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
