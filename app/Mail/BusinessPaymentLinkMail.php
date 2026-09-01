<?php

namespace App\Mail;

use App\Models\Business;
use App\Models\BusinessPaymentLink;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BusinessPaymentLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public Business $business;
    public BusinessPaymentLink $link;

    public function __construct(Business $business, BusinessPaymentLink $link)
    {
        $this->business = $business;
        $this->link = $link;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Business Renewal Payment Link - ' . $this->business->business_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.business_payment_link',
        );
    }
}
