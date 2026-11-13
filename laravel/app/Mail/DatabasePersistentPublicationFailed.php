<?php

namespace App\Mail;

use App\Exceptions\ReferenceException; 
use App\Mail\Mailable;
use App\Models\Database;
use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DatabasePersistentPublicationFailed extends Mailable
{
	use Queueable, SerializesModels;

	public string $radar_details;
	public string $exceptionReference = '';

	/**
	 * Create a new message instance.
	 */
	public function __construct(
		public Database $database,
		string|ReferenceException $radar_details, 
		public $admin = false,
		public User|string|null $actor = null
	) {
		parent::__construct(actor: $actor); // call our Mailable constructor to initialise "$actor" property

		Log::debug('Mailable radar_details type', [
			'type' => get_debug_type($radar_details),
			'class_if_object' => is_object($radar_details) ? get_class($radar_details) : 'not an object'
		]);

		$this->exceptionReference = "TODO: the exception reference will go here!";
		// 1. Check if the exception is specifically a ReferenceException
		if ($radar_details instanceof ReferenceException) {
			// Extract and override the reference code automatically!
			$this->exceptionReference = $radar_details->getReference();
		}

		// If it's an Exception, extract the message (and maybe the file/line)
		$this->radar_details = "TODO: the details will go here!";
		if ($radar_details instanceof ReferenceException) {
			$this->radar_details = sprintf("Error: %s, Previous Error: %s", $radar_details->getMessage(), $radar_details->getPrevious()?->getMessage() ?? 'No previous error message');
		} else {
			$this->radar_details = $radar_details;
		}
	}

	/**
	 * Get the message envelope.
	 */
	public function envelope(): Envelope
	{
			return new Envelope(
					subject: config('app.name') . ': ' . ($this->admin ? '(ADMIN): ' : '') . ' Persistent publication of the database "' . $this->database->title . '"' . ($this->admin ? ' (' . $this->database->id . ')' : '') . ' failed',
			);
	}

	/**
	 * Get the message content definition.
	 */
	public function content(): Content
	{
		return new Content(
			view: 'mail.database-persistent-publication-failed',
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
