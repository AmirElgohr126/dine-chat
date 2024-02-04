<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $otp;
    public $expirationTime;
    /**
     * Create a new message instance.
     */
    public function __construct($name, $otp, $expirationTime)
    {
        $this->name = $name;
        $this->otp = $otp;
        $this->expirationTime = $expirationTime;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send OTP',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        return $this->view('dashboard_admin.opt_super_admin');
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
