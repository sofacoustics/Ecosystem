<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

use App\Models\Datafile;
use App\Models\Datasetdef;
use App\Models\Datafiletype;

class DatafileListener extends Component
{
    public string $id = "undefined";
    public string $asset = "";
    public Datafile $datafile;
    public Datasetdef $datasetdef;
    public Datafiletype $datafiletype;

    public function mount(Datafile $datafile)
    {
        app('log')->info('DatafileListener::mount()');
        $this->datafile = $datafile;
        $this->id = $datafile->id;
        $this->datasetdef = $datafile->datasetdef;
        $this->datafiletype = $datafile->datasetdef->datafiletype;

    }
    public function mounted()
    {
        app('log')->info('DatafileListener::mounted()');
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
        if($this->id == "undefined")
            return view('livewire.datafile-listener');
        else{
            $this->datasetdef = $this->datafile->datasetdef;
            $this->datafiletype = $this->datasetdef->datafiletype;
        }
        //dd($this->datafile);
        switch($this->datafile->datasetdef->datafiletype->id) {
        case 0:
        case 1:
        default:
            return view('livewire.datafile-listener');
        case 3:
            return view('livewire.datafiletypes/datafiletype-3-detail');
        }
        //return view('livewire.datafile-listener');
        //return view('livewire.datafile-listener');
    }
}
