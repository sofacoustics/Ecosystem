<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Events\Test;

class ChannelListener extends Component
{
	public string $message = "uninitialised";

	/*
	public function getListeners()
	{
		return [
			"echo:sonicom-ecosystem,Test" => 'onTest',
		];
	}*/


    public function mount()
    {
        $this->message = "mounted";
    }

	//jw:note doesn't seem to have an effect
	public function rendered($view,$html)
    {
        $this->message = "rendered";
    }

	#[On('echo:sonicom-ecosystem,.test-event')]
	public function onTest($event)
	{
		$this->message = $event['message'];
		//print_r($event);
		//$this->message = "onTest";
		//$this->message = "onTest()";
		//dd($event['message']);

        app('log')->info('ChannelListener::onTest()');
	}


    public function render()
    {
        return view('livewire.channel-listener');
    }
}
