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
        //session()->flash('status', "Datafile (id=$datafile->id) is being saved to the database");
        $datafile->save(); // save so datafile has ID (necessary for saving file)
        $directory = $datafile->directory();
        //$this->dispatch('saving-file', name: $datafile->name); // dispatch a browser event
        $this->dispatch('showFlashMessage', ['type' => 'success', 'message' => 'storeAs']);
        //session()->flash('status', "Datafile $datafile->name is being saved to disk");
        $this->file->storeAs("$directory", "$datafile->name", 'sonicom-data');
        // clean up
        $this->file->delete();
        //jw:note 'navigate: true' means that livewire retrieves the page in the background.
        //jw:note This means we may be able to load multiple files concurrently.
        //$this->redirect(url()->previous(), navigate: true);
        $this->redirect(url()->previous()); //jw:note if the whole dataset view was a livewire component, then we wouldn't have to redirect.
    }

    public function render()
    {
        return view('livewire.datafile-upload');
    }
}
