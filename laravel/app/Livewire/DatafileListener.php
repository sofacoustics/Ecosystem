<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class DatafileListener extends Component
{
    public string $id = "undefined";

    public function mounted()
    {
        $this->id = "mounted";
    }

    #[On('echo:sonicom-ecosystem,.datafile-processed')]
	public function onDatafileProcessed($event)
	{
		$this->id = $event['id'];
        app('log')->info('DatafileListener::onDatafileProcessed()');
	}

    public function render()
    {
        return view('livewire.datafile-listener');
    }
}
