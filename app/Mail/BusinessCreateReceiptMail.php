<?php

namespace App\Mail;

use App\Models\Business;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;

class BusinessCreateReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Business $business;
    public ?User $user;
    public string $receiptNo;
    public float $amount;
    public string $paymentStatus;
    public ?string $paymentId;

    /**
     * Create a new message instance.
     */
    public function __construct(Business $business, ?User $user = null, ?float $amount = null, ?string $paymentStatus = null, ?string $paymentId = null)
    {
        $this->business = $business;
        $this->user = $user ?? $business->user;
        $this->amount = $amount ?? (float)($business->payment_amount ?? \App\Models\Setting::get('business_registration_fee', '500'));
        $this->paymentStatus = $paymentStatus ?? ($business->payment_status ?? 'paid');
        $this->paymentId = $paymentId ?? $business->payment_id;
        $this->receiptNo = 'RCP-BIZ-' . date('Y') . '-' . sprintf('%05d', $business->id);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Business Registration Receipt #' . $this->receiptNo . ' - ' . $this->business->business_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.business_receipt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = Pdf::loadView('emails.receipt_pdf.business', [
            'business' => $this->business,
            'user' => $this->user,
            'receiptNo' => $this->receiptNo,
            'amount' => $this->amount,
            'paymentStatus' => $this->paymentStatus,
            'paymentId' => $this->paymentId,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Business_Receipt_' . $this->receiptNo . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
