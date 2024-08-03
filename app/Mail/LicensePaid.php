<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicensePaid extends Mailable
{
    use Queueable, SerializesModels;

    public $licenseUser;
    public $amount;
    public $value;

    /**
     * Create a new message instance.
     *
     * @param $licenseUser
     * @param $amount
     * @param $value
     * @param $license
     */
    public function __construct($licenseUser, $amount, $value, $license)
    {
        $this->licenseUser = $licenseUser;
        $this->amount = $amount;
        $this->value = $value;
        $this->license = $license;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New License Paid',
            tags: ['license payment'],
            metadata: [
                'Description' => 'License Payment',
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.license-paid',
            with: [
                'licenseUser' => $this->licenseUser,
                'amount' => $this->amount,
                'value' => date('Y-m-d H:i:s', strtotime($this->value)),
                'name' => $this->license->name,
                'url' => url('/user/payments')
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
}
