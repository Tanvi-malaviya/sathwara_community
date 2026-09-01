<?php

namespace App\Mail;

use App\Models\User;
use App\Models\MemberProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Mail\Mailables\Attachment;
use App\Services\ReceiptPdfService;

class MembershipPurchaseReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?MemberProfile $profile;
    public string $receiptNo;
    public float $amount;
    public string $paymentStatus;
    public ?string $paymentId;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ?MemberProfile $profile = null, ?float $amount = null, ?string $paymentStatus = null, ?string $paymentId = null)
    {
        $this->user = $user;
        $this->profile = $profile ?? $user->memberProfile;
        $paidAmount = (float) $user->payment_amount;
        $this->amount = $amount ?? ($paidAmount > 0 ? $paidAmount : (float) \App\Models\Setting::get('member_signup_fee', '1000'));
        $this->paymentStatus = $paymentStatus ?? ($user->payment_status ?? 'paid');
        $this->paymentId = $paymentId ?? $user->payment_id;
        $this->receiptNo = \App\Services\ReceiptNumberService::assign($user, 'receipt_no');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Membership Purchase Receipt #' . $this->receiptNo . ' - ' . config('app.name', 'Shree Satwara Gnati Mandal, Ahmedabad'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.membership_receipt',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $pdf = ReceiptPdfService::make('emails.receipt_pdf.membership', [
            'user' => $this->user,
            'profile' => $this->profile,
            'receiptNo' => $this->receiptNo,
            'amount' => $this->amount,
            'paymentStatus' => $this->paymentStatus,
            'paymentId' => $this->paymentId,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Membership_Receipt_' . str_replace('/', '-', $this->receiptNo) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
