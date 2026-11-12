<?php

namespace App\Mail;

use App\Models\Database;
use App\Mail\Mailable;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DatabaseDOIAssignmentFailed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
	public function __construct(
		public Database $database,
		public $admin = false,
		public $exceptionReference = "",
		public $details = "" 
	) {
		parent::__construct(); // call our Mailable constructor to initialise "$actor" property
	}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name') . ': ' . ($this->admin ? '(ADMIN): ' : '') . ' DOI assignment to the database "' . $this->database->title . '"' . ($this->admin ? ' (' . $this->database->id . ') failed!' : ' failed!'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.database-doi-assignment-failed',
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
