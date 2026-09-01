<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterEmailOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($otp, $email)
    {
        $this->otp   = $otp;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Shree Satwara Gnati Mandal, Ahmedabad — Email Verification OTP',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.register-email-otp',
        );
    }
}
