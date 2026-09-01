<?php

namespace App\Mail;

use App\Models\Business;
use App\Models\BusinessPaymentLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Services\ReceiptPdfService;

class BusinessRenewalReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Business $business;
    public BusinessPaymentLink $link;
    public string $receiptNo;
    public float $amount;
    public string $paymentStatus;
    public ?string $paymentId;

    public function __construct(Business $business, BusinessPaymentLink $link)
    {
        $this->business = $business;
        $this->link = $link;
        $this->amount = (float) $link->amount;
        $this->paymentStatus = 'paid';
        $this->paymentId = $link->razorpay_payment_id;
        $this->receiptNo = \App\Services\ReceiptNumberService::assign($link, 'receipt_no');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Business Renewal Receipt #' . $this->receiptNo . ' - ' . $this->business->business_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.business_renewal_receipt',
        );
    }

    public function attachments(): array
    {
        $pdf = ReceiptPdfService::make('emails.receipt_pdf.business_renewal', [
            'business' => $this->business,
            'receiptNo' => $this->receiptNo,
            'amount' => $this->amount,
            'paymentStatus' => $this->paymentStatus,
            'paymentId' => $this->paymentId,
        ]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'Business_Renewal_Receipt_' . str_replace('/', '-', $this->receiptNo) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
