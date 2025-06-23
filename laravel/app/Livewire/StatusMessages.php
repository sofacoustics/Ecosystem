<?php
// https://www.perplexity.ai/search/how-can-i-implement-a-status-c-3XTc6EIYQ42SNXgoNpX2XQ#0

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

/*
 * Listen for 'status-message' events and add messages
 * to a list.
 * Messages are then displayed for 3 seconds and then removed
 */
class StatusMessages extends Component
{
	public $messages = [];

	protected $listeners = [
		'status-message' => 'appendStatusMessage',
	];

	public function appendStatusMessage($message)
	{
		if($message != "")
		{
			$this->messages[] = $message;
			$this->dispatch('status-message-added');
		}
	}

	public function removeFirstMessage()
	{
		array_shift($this->messages);
	}

	public function render()
	{
		return view('livewire.status-messages');
	}
}
