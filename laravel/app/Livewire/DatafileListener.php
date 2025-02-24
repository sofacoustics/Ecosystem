<?php

namespace App\Livewire;

use Illuminate\Support\Facades\View;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Datafile;
use App\Models\Datasetdef;
use App\Models\Datafiletype;
use App\Models\Widget;

class DatafileListener extends Component
{
    public string $id = "undefined";
    public string $asset = "";
    public Datafile $datafile;
    public Datasetdef $datasetdef;
    public Datafiletype $datafiletype;
    public ?Widget $widget; // may be null

    public function mount(Datafile $datafile)
    {
        app('log')->info('Livewire DatafileListener::mount()');
        $this->datafile = $datafile;
        $this->id = $datafile->id;
        $this->datasetdef = $datafile->datasetdef;
        $this->datafiletype = $datafile->datasetdef->datafiletype;
        $this->widget = $datafile->datasetdef->widget;

    }
    public function mounted()
    {
        app('log')->info('Livewire DatafileListener::mounted()');
        $this->id = "mounted";
    }

    #[On('echo:sonicom-ecosystem,.datafile-processed')]
	public function onDatafileProcessed($event)
	{
		$this->id = $event['id'];
        app('log')->info("DatafileListener::onDatafileProcessed(".$event['id'].")");
	}

    public function render()
    {
        $view = "livewire.datafiles.".$this?->widget && $this->widget?->view ? $this->widget->view : 'generic' ;
        if(!View::exists($view))
            $view = 'livewire.datafiles.generic';
        return view($view);
    }
}
