<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BulkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'AP Aslam Holy Quran Award 2024 - Competition Details',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bulk-mail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}