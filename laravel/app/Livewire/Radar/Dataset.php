<?php

namespace App\Livewire\Radar;

use App\Livewire\Forms\Radar\Dataset as DatasetForm;

use \App\Data\RadardatasetpureData;
use \App\Http\Controllers\Api\Radar\DatasetController as RadardatasetController;
use \App\Models\Database;

use App\Traits\Radar\Rules\Dataset as Radardatasetrules;

use Livewire\Component;

class Dataset extends Component
{
    //public DatasetForm $form;
    public \App\Models\Database $database; // our database so we can update it.
    // https://spatie.be/docs/laravel-data/v4/advanced-usage/use-with-livewire
    public RadardatasetpureData $radardataset;

    /*
     * Use rules from Radardatasetrules, but modify so they work here
     * by prepending 'radardataset.' to each key.
     */
    public function rules()
    {
        $rules = Radardatasetrules::rules();
        $rules = array_combine(
            array_map(function($key){ return 'radardataset.'.$key; }, array_keys($rules)),
            $rules
        ); // https://www.itsolutionstuff.com/post/how-to-add-prefix-in-each-key-of-php-arrayexample.html
        return $rules;
    }

    /*
     * Use messages from Radardatasetrules, but modify so they work here
     * by prepending 'radardataset.' to each key.
     */
    public function messages()
    {
        $messages = Radardatasetrules::messages();
        $messages = array_combine(
            array_map(function($key){ return 'radardataset.'.$key; }, array_keys($messages)),
            $messages
        ); // https://www.itsolutionstuff.com/post/how-to-add-prefix-in-each-key-of-php-arrayexample.html
        return $messages;
    }

    /*
     * Fill the local data structure with data from the model
     */
    public function mount(\App\Models\Database $database)
    {
        $this->database = $database;
        //$this->radardataset = RadardatasetpureData::from($database->radardataset); //SongData::from(Song::findOrFail($id));
        $this->radardataset = $database->radardataset; //SongData::from(Song::findOrFail($id));

        //dd($this->radardataset);
    }

    /*
     * Save the form data and upload to RADAR
     */
    public function save()
    {
        //dd($this->rules());
        //dd($this->radardataset);
        $this->validate();

        // update our database->radardataset values
        $this->database->radardataset = RadardatasetpureData::from($this->radardataset);
        $this->database->save();

        // upload to RADAR
        $dataset_id = $this->radardataset->id;
        $newjson = $this->radardataset->toJson();

        $radar = new RadardatasetController;
        $body = $radar->put("/datasets/$dataset_id", $newjson);

        session()->flash('status', 'RADAR successfully updated');
        //dd($body);
    }

    public function render()
    {
        //dd($this->radardataset);
        return view('livewire.radar.dataset');
    }

    // https://livewire.laravel.com/docs/lifecycle-hooks#update
    public function updated($property)
    {
        /*
         * Set additonalSubjectAreaName to "" if controlledSubjectAreaname is not 'OTHER'
         */
        if (preg_match('/radardataset\.descriptiveMetadata\.subjectAreas\.subjectArea\.(.*)/', $property))// === 'radardataset.descriptiveMetadata.subjectAreas.subjectArea.1.controlledSubjectAreaName')
        {
            // parse property
            $a = explode('.', $property);
            if($this->radardataset->descriptiveMetadata->subjectAreas->subjectArea[$a[4]]['controlledSubjectAreaName'] != 'OTHER')
            {
                $this->radardataset->descriptiveMetadata->subjectAreas->subjectArea[$a[4]]['additionalSubjectAreaName'] = '';
            }
        }
    }

    public function testOnChange($parameter)
    {
        dd($parameter);
    }
}
