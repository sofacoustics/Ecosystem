<?php

namespace App\Mail;

use App\Models\Tool;
use App\Mail\Mailable;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ToolDOIAssigned extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
	public function __construct(
		public Tool $tool,
		public $admin = false
	) {
		parent::__construct(); // call our Mailable constructor to initialise "$actor" property
	}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
		{
        return new Envelope(
            subject: config('app.name') . ': ' . ($this->admin ? '(ADMIN): ' : '') . 'DOI assigned to the tool "' . $this->tool->title . '"' . ($this->admin ? ' (' . $this->tool->id . ')' : ''),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
		{
        return new Content(
            view: 'mail.tool-doi-assigned',
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
