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
        $previewText = 'എപി അസ്ലം ഹോളി ഖുർആൻ അവാർഡ് 2024 ന്റെ പ്രാഥമിക റൗണ്ട് മത്സരത്തിൽ താങ്കൾ തിരഞ്ഞെടുത്ത മേഖലയിലെ മത്സരത്തിന്റെ സമയക്രമം താഴെ കൊടുക്കുന്നു.';

        return new Envelope(
            subject: 'AP Aslam Holy Quran Award 2024 - Preliminary Round Competition Details',
            metadata: [
                'preview_text' => $previewText,
            ],
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