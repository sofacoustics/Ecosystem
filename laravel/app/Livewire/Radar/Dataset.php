<?php

namespace App\Livewire\Radar;

use App\Livewire\Forms\Radar\Dataset as DatasetForm;

use \App\Http\Controllers\Api\Radar\DatasetController as Radardataset;
use \App\Models\Database;

use Livewire\Component;

class Dataset extends Component
{
    public DatasetForm $form;
    public \App\Models\Database $database;


    //public function mount(\App\Data\RadardatasetpureData $dataset)
    public function mount(\App\Models\Database $database)
    {
        //dd($database);
        $this->form->setDataset($database->radardataset);
    }

    public function save()
    {
        $this->validate();
        //dd($this->form->all());
        //jw:tmp
        $this->database->radardataset->descriptiveMetadata->title="ARI B modified by livewire";

        $dataset_id = $this->database->radardataset->id;
        $json = $this->database->radardataset->toJson();

        $radar = new Radardataset;
        $body = $radar->put("/datasets/$dataset_id", $json);
    }

    public function render()
    {
        return view('livewire.radar.dataset');
    }
}
