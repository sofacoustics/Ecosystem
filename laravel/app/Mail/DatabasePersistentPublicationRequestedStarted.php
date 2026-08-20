<?php

namespace App\Mail;

use App\Mail\Mailable;
use App\Models\Database;
use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DatabasePersistentPublicationRequestedStarted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
	public function __construct(
		public Database $database,
		public $admin = false,
		public User|string|null $actor = null
	) {
		parent::__construct(actor: $actor); // call our Mailable constructor to initialise "$actor" property
	}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ': ' . ($this->admin ? '(ADMIN): ' : '') . ' Persistent publication requested for the database "' . $this->database->title . '"' . ($this->admin ? ' (' . $this->database->id . ')' : '') . ' - uploading.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.database-persistent-publication-requested-started',
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
