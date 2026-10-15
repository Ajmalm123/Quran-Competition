<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizParticipantPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user,
        public string $plainPassword,
        public string $loginUrl = '',
        public string $reason = 'update' // 'forgot_password', 'admin_update', 'update'
    ) {
        if (empty($this->loginUrl)) {
            $this->loginUrl = url('/quiz');
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->reason) {
            'forgot_password' => 'നിങ്ങളുടെ പുതിയ ക്വിസ് പാസ്‌വേഡ് - Friday Night Quiz',
            'admin_update' => 'Your Friday Night Quiz Login Password - Aslam Quran Award',
            default => 'Friday Night Quiz - നിങ്ങളുടെ ലോഗിൻ വിവരങ്ങൾ',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.quiz-password',
            with: [
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
                'loginUrl' => $this->loginUrl,
                'reason' => $this->reason,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
