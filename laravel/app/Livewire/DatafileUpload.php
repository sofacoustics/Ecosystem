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

    public $file;
    public $datafile;
    public Dataset $dataset;
    public Datasetdef $datasetdef;
    public $callingUrl; // URL to return to after saving file

    public function updated($property)
    {
        if("$property" == 'file')
        {
            /*
             * Save the file immediately, so user doesn't have to press a 'submit' button
             */
            $this->save();
        }
    }

    public function save()
    {
        //
        // if datafile doesn't exist, create it here!
        //
        if(!$this->datafile)
        {
            // create new Datafile
            $datafile = new Datafile;
            // set mandatory fields
            $datafile->dataset_id = $this->dataset->id;
            $datafile->datasetdef_id = $this->datasetdef->id;
        }
        else
        {
            // remove old files when editing existing file
            $datafile->clean(); //jw:todo
            //jw:todo
            //Storage::disk('public')->delete("$datafile->directory()/*");

        }
        $datafile->name = $this->file->getClientOriginalName();
        $datafile->save(); // save so datafile has ID (necessary for saving file)
        $directory = $datafile->directory();
        $this->file->storeAs("$directory", "$datafile->name", "public");
        $this->redirect(url()->previous()); //jw:note if the whole dataset view was a livewire component, then we wouldn't have to redirect.
    }

    public function render()
    {
        return view('livewire.datafile-upload');
    }
}
