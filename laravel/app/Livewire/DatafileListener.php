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
    public Widget $widget;

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
        $view = "livewire.datafiles.".$this->widget->view;
        if(!isset($this->datafile) || !View::exists($view))
        {
            $view = 'livewire.datafiles.generic';
        }
        return view($view);
        //dd($this->datafile->datasetdef->datafiletype->id);
        if($this->id == "undefined")
            return view('livewire.datafiletype-generic');
        else{
            $this->datasetdef = $this->datafile->datasetdef;
            $this->datafiletype = $this->datasetdef->datafiletype;
        }
        //jw:todo do these really need to be livewire views?
        //jw:note This does not neccessarily have to be a livewire view.
        $view = 'livewire.datafiletypes.datafiletype-'.$this->datafiletype->id;
        //$view = 'datafiletypes.datafiletype-'.$this->datafiletype->id;
        if(!isset($this->datafile) || !View::exists($view))
        {
            $view = 'livewire.datafiletypes.datafiletype-generic';
        }
        return view($view);
    }
}
