<?php
// https://www.perplexity.ai/search/how-can-i-implement-a-status-c-3XTc6EIYQ42SNXgoNpX2XQ#0

namespace App\Livewire;

use Livewire\Component;

class StatusMessages extends Component
{
    public $messages = [];

    protected $listeners = ['appendStatusMessage'];

    public function appendStatusMessage($message)
    {
        $this->messages[] = $message;
        $this->dispatch('status-message-added');
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
