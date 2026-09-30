<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public array $data
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['title'] ?? 'Notifikasi Tiket',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket-notification',
            with: [
                'user' => $this->user,
                'data' => $this->data,
            ],
        );
    }
}