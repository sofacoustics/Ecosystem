<?php

namespace App\Livewire;

use Illuminate\Routing\UrlGenerator;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Datafile;
use App\Models\Dataset;
use App\Models\Datasetdef;

class DatafileUpload extends Component
{
    use WithFileUploads; // trait necessary for livewire upload

    // wire:model
    public $file;

    // component parameters (:dataset, :datasetdef)
    public Dataset $dataset;
    public Datasetdef $datasetdef;
    // component parameter (:datafile) if editing existing datafile
    public ?Datafile $datafile;

    public function mount()
    {
        //jw:note doesn't appear to work. $this->authorize('create', $this->dataset);
    }

    public function updated($property)
    {
        if ("$property" == 'file') {
            /*
             * Save the file immediately, so user doesn't have to press a 'submit' button
             */
            $this->save();
        }
    }

    public function save()
    {
        $this->authorize('update', $this->dataset); // check if dataset can be modified
        //
        // if datafile doesn't exist, create it here!
        //
        if (!isset($this->datafile)) {
            // create new Datafile
            $datafile = new Datafile();
            // set mandatory fields
            $datafile->dataset_id = $this->dataset->id;
            $datafile->datasetdef_id = $this->datasetdef->id;
        } else {
            // remove old files when editing existing file
            $this->datafile->clean(); //jw:todo
        }
        $datafile->name = $this->file->getClientOriginalName();
        $datafile->save(); // save so datafile has ID (necessary for saving file)
        $directory = $datafile->directory();
        $this->file->storeAs("$directory", "$datafile->name", 'sonicom-data');
        // clean up
        $this->file->delete();
        $this->redirect(url()->previous()); //jw:note if the whole dataset view was a livewire component, then we wouldn't have to redirect.
    }

    public function render()
    {
        return view('livewire.datafile-upload');
    }
}
