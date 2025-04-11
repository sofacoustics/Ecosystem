<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\MessageEvent;

class MessageComponent extends Component
{
    public $message = "";
    public $conversation = [];

    public function submitMessage(){
        MessageEvent::dispatch($this->message);
    }

    #[On('echo:our-channel,MessageEvent')]
    public function listenForMessage($data){
        $this->conversation[] = $data['theMessage'];
    }


    public function render()
    {
        return view('livewire.message-component');
    }
}
