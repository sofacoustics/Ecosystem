<?php

namespace App\Mail;

use App\Models\Comment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewComment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Comment $comment
    )
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if($this->comment->commentable_type == "App\Models\Database")
            return new Envelope(
                subject: config('app.name') . ': New Comment on database '. $this->comment->commentable->title,
            );
        else
            return new Envelope(
                subject: config('app.name') . ': New Comment on tool ' . $this->comment->commentable->title,
            );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.new-comment',
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
