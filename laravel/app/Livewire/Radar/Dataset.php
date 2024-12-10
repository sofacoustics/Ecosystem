<?php

namespace App\Livewire\Radar;

use App\Livewire\Forms\Radar\Dataset as DatasetForm;

use \App\Data\RadardatasetcreatorData;
use \App\Data\RadardatasetpureData;
use \App\Data\RadardatasetrightsholderData;
use \App\Data\RadardatasetsubjectareaData;
use \App\Data\RORData;
use \App\Http\Controllers\Api\Radar\DatasetController as RadardatasetController;
use \App\Models\Database;
use \App\Models\Radar\Dataset as DatasetModel;

use App\Traits\Radar\Rules\Dataset as Radardatasetrules;

use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Log\LogManage;

use Livewire\Component;

class Dataset extends Component
{
    //public DatasetForm $form;
    public \App\Models\Database $database; // our database so we can update it.
    // https://spatie.be/docs/laravel-data/v4/advanced-usage/use-with-livewire
    public RadardatasetpureData $radardataset;
    public \App\Models\Radar\Dataset $dataset; // class to manipulate the $radardataset


    // ROR type ahead (https://owenconti.com/posts/building-a-search-drop-down-component-with-laravel-livewire)
    public $rorQuery = []; // each rightsHolder has it's own query!
	public $rorResult;
    public RORData $rorResults;
	public $rorid; // the resulting id to save
	public $rorname; // the resulting name to save
	public $currentRightsHolder = '';
    public $query;
    public $contacts;
    public $highlightIndex;


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
		$this->rorReset(); // reset ROR stuff
        $this->database = $database;
        //$this->radardataset = RadardatasetpureData::from($database->radardataset); //SongData::from(Song::findOrFail($id));
        $this->radardataset = $database->radardataset; //SongData::from(Song::findOrFail($id));


        //$this->dataset = new \App\Models\Radar\Dataset($this->radardataset);//DatasetModel;

        //$this->radardataset->id = "asdf";
        //$dataset->setCreatorType(0, "person");
        //$dataset->setCreatorType(0, "institution");


        //dd($this->radardataset);
    }

    /*
     * Save the form data and upload to RADAR
     */
    public function save()
    {

		//jw:todo Authorize!
		$this->authorize('update', $this->database);

        //dd($this->rules());
        //dd($this->radardataset);
        // validate using App\Traits\Radar\Rules

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

	public function updating($property)
    {

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
            if($this->radardataset->descriptiveMetadata->subjectAreas->subjectArea[$a[4]]->controlledSubjectAreaName != 'OTHER')
            {
                $this->radardataset->descriptiveMetadata->subjectAreas->subjectArea[$a[4]]->additionalSubjectAreaName = '';
            }
        }
        else if (preg_match('/radardataset\.descriptiveMetadata\.rightsHolders\.rightsHolder\.(.*).value/', $property))// === 'radardataset.descriptiveMetadata.subjectAreas.subjectArea.1.controlledSubjectAreaName')
		{
            $a = explode('.', $property);
			$key = $a[4];
			$value =$this->radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$key]->value;
			$this->js("console.log(\"updated value: $value \")");
			$this->currentRightsHolder = $key;
			$response = $this->rorSearch($this->radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$key]->value);
        }
        else if (preg_match('/radardataset\.descriptiveMetadata\.creators\.creator\.(.*).nameIdentifier.0.nameIdentifierScheme/', $property))// === 'radardataset.descriptiveMetadata.subjectAreas.subjectArea.1.controlledSubjectAreaName')
        {
            // modify schemeURI and nameIdentifier based on value
            $a = explode('.', $property);
            $key = $a[4];
            //dd($this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]);
            $nameIdentifierScheme = $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->nameIdentifierScheme;
            //dd($nameIdentifierScheme);
            if("$nameIdentifierScheme" === "ROR")
            {
                $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->schemeURI = "https://ror.org/";
                $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->value = "";

            }
            else if("$nameIdentifierScheme" === "ORCID")
            {
                $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->schemeURI = "http://orcid.org/";
                $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->value = "";
            }
            else
            {
                // OTHER
                $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->schemeURI = "";
                $this->radardataset->descriptiveMetadata->creators->creator[$key]->nameIdentifier[0]->value = "";
            }
        }
	}

    public function updatingRorquery($array)
    {
		if(is_array($array) && array_key_exists('value', $array))
		{
			$string = $array['value'];
		}
		else
			$string = "you wont find this institution here";

		dd($this->rorQuery);

		//dd($string);
        // do ROR search
        //$url = "https://api.ror.org/organizations?query=%22$string%22";
        $url = "https://api.ror.org/v2/organizations?query=%22$string%22"; // returns different structure with more levels than url below
        $url = htmlspecialchars($url);
        $response = Http::withOptions([
            'debug' => false,
        ])->get("$url");

		//$this->rorResults = json_decode($response->body(),true);
		//$this->rorResults = json_decode($response->body(),true);
		//dd($response->body());

		$this->rorResults = RORData::from($response->body());
        //$this->rorResults = json_decode($response->body(),true);

        //dd($body);
    }


    public function updatedRorquery()
    {
        //dd($this->rorQuery);
    }

    public function testOnChange($parameter)
    {
        dd($parameter);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // creator
    //
    public function addCreator()
    {
        $creator = RadardatasetcreatorData::from(['creatorName' => '']);
        $this->radardataset->descriptiveMetadata->creators->creator[] = $creator;
    }

    public function removeCreator($index)
    {
        array_splice($this->radardataset->descriptiveMetadata->creators->creator, $index, 1);
    }

    public function setCreatorType($key, $type)
    {
        $creator = new \App\Models\Radar\Dataset\Creator($this->radardataset->descriptiveMetadata->creators->creator[$key]);//DatasetModel;
        $creator->setCreatorType($type);

        //dd($this->radardataset->descriptiveMetadata->creators->creator[$key]);//DatasetModel;
    }

    ////////////////////////////////////////////////////////////////////////////////
    // subjectArea
    //
    public function addSubjectArea()
    {
        $subjectArea = RadardatasetsubjectareaData::from(['controlledSubjectAreaName' => 'AGRICULTURE']);
        //dd($subjectArea);
        //dd($this->radardataset);
        $this->radardataset->descriptiveMetadata->subjectAreas->subjectArea[] = $subjectArea;
        //dd($this->radardataset);
    }

    public function removeSubjectArea($index)
    {
        //dd($index);
        $subjectArea = new RadardatasetsubjectareaData;

        //unset($this->radardataset->descriptiveMetadata->subjectAreas->subjectArea[$index]);
        array_splice($this->radardataset->descriptiveMetadata->subjectAreas->subjectArea, $index, 1);
        //dd($this->radardataset);
    }


    ////////////////////////////////////////////////////////////////////////////////
    // ROR type ahead

 	function rorReset()
    {
		//$this->rorQuery = '';
		$this->currentRightsHolder = '';
		$this->rorResult = 'uninitialised';
		unset($this->rorResults);
        $this->contacts = [];
        $this->highlightIndex = 0;
    }

    public function rorIncrementHighlight()
    {
        if ($this->highlightIndex === count($this->contacts) - 1) {
            $this->highlightIndex = 0;
            return;
        }
        $this->highlightIndex++;
    }

    public function rorDecrementHighlight()
    {
        if ($this->highlightIndex === 0) {
            $this->highlightIndex = count($this->contacts) - 1;
            return;
        }
        $this->highlightIndex--;
    }

    public function rorSelectEntry()
    {
        $contact = $this->contacts[$this->highlightIndex] ?? null;
        if ($contact) {
            $this->redirect(route('show-contact', $contact['id']));
        }
    }

	//public function rorSet($name, $id)
	public function rorSet($rightsHolderKey, $id, $name)
	{
		//dd($this->radardataset->descriptiveMetadata->rightsHolders->rightsHolder);
		$this->radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$rightsHolderKey] = RadardatasetrightsholderData::from(['value' => "$name", 'schemeURI' => 'https://ror.org', 'nameIdentifier' => "$id", 'nameIdentifierScheme' => 'ROR']);
		//$this->radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$rightsHolderKey] = ['value' => "$name", 'schemeURI' => 'https://ror.org', 'nameIdentifier' => "$id", 'nameIdentifierScheme' => 'ROR'];
		//dd($this->radardataset->descriptiveMetadata->rightsHolders->rightsHolder[$rightsHolderKey]);
		// logging: https://medium.com/@zacktrobertson/using-console-log-in-laravel-livewire-389b5be4ffae
		$this->js("console.log(\"rightsHolderKey: $rightsHolderKey\")");
		$this->rorReset();
	}

    public function updatedQuery()
    {
        $this->contacts = Contact::where('name', 'like', '%' . $this->query . '%')
            ->get()
            ->toArray();
    }

	private function rorSearch($string)
	{
        $url = "https://api.ror.org/v2/organizations?query=".rawurlencode("\"$string\""); // returns different structure with more levels than url below
        //$url = htmlspecialchars($url);
        //$url = rawurlencode($url);
		$this->js("console.log(\"url: $url\")");
        $response = Http::withOptions([
            'debug' => false,
        ])->get("$url");

		if($response->status() == 200)
		{
			//dd($response);
			$status = $response->status();

			$this->rorResults = RORData::from($response->body());
			$nresults = $this->rorResults->number_of_results;
			//$this->js("console.log(\"rorSearch('$string'\) response status = $status number_of_results: '".$this->rorResults->number_of_results."'\"\)");
			$this->js("console.log(\"string: $string url: $url nresults: $nresults\")");
			return $response;
		}
		else
			$this->js("console.log(\"Failed to get ROR response ($response->status())\")");
	}

}
