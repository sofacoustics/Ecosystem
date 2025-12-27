<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\SkipHydration;

use App\Models\Database;
use App\Models\Datafile;
use App\Models\Dataset;
use App\Models\Datasetdef;

/*
 * Bulk upload of database files.
 */
class DatabaseUpload extends Component
{
	use WithFileUploads; // trait necessary for livewire upload

	public Database $database;
	public $datasetdefIds; // array of datasetdef ids
	public $datasetnamefilter;
	public $descriptionfilter;
	public $datafilenamefilters= [];
	public $datasetsCount = 0;

	public bool $started = false;
	public bool $cancelled = false;
	public bool $finished = false;
	public $status = "";

	public $directory;

	public array $uploads;			// files uploaded to livewire-tmp
	public array $uploadsMetadata;  // additional metadata for '$uploads': 'datasetName', 'datasetDesc', 'datasetdefId', 'fileName'
	public array $existingFilesMetadata; // a list of existing datafiles including their dataset names and datasetdefIds.

	public array $saved;    // a list of names of the files which have been saved to the database;
	public array $filtered; // a list of names of the files which fit the filter criteria
	public array $uploaded; // a list of names of the files which have been uploaded

	public $overwriteExisting; // set to true, if existing datafiles should be overwritten.

	public $progress;
	public $uploading;

	public $nFilesExisting = -1; // The number of datafiles which already exist in the database (jw:todo not sure if this works with pending files resulting from filtering)

	private $debugLevel = 1;
	private $debugIndent = 0;

	public $dto = null; // a DataTranfer object containing list of files to upload

	public function mount(Database $database)
	{
		$this->database = $database->load('datasetdefs','datasets'); // https://laracasts.com/discuss/channels/livewire/livewire-wiremodel-with-model-relationship
		$this->datasetdefIds = $this->database->datasetdefs->pluck('id');
		$this->datasetnamefilter = $database->bulk_upload_dataset_name_filter;
		$this->descriptionfilter = $database->bulk_upload_description_filter;
		foreach($this->database->datasetdefs as $datasetdef)
		{
			$this->datafilenamefilters[$datasetdef->id] = $datasetdef->bulk_upload_filename_filter;
		}
		$this->overwriteExisting = true; // session()->get('sonicomEcosystemBulkUploadOverwrite') == 1 ? true : false;
		$this->debug(1, "Mounted");
	}

	public function boot()
	{
	}


	public function start()
	{
		$this->started = true;
	}

	public function updatedOverwriteExisting($param)
	{
		session()->put('sonicomEcosystemBulkUploadOverwrite', "$param");
	}
	
		// Updates the filter patterns
	public function updatedDatafilenamefilters($value, $key) // https://dev.to/zaxwebs/accessing-updated-index-in-livewire-48oc
	{
		$datasetdef = Datasetdef::find($key);
		$datasetdef->bulk_upload_filename_filter = "$value";
		$datasetdef->save();
		$this->datafilenamefilters[$key] = "$value";
	}

	public function updated($field, $value) // General 'updated' function
	{
		$property = strtok($field, '.');
		$key = strtok('.');
		switch($property)
		{
			case 'datasetnamefilter':
				$this->database->bulk_upload_dataset_name_filter = $this->datasetnamefilter;
				$this->database->save();
				break;
			case 'descriptionfilter':
				$this->database->bulk_upload_description_filter = $this->descriptionfilter;
				$this->database->save();
				break;
			case 'uploads':
				break;
			case 'uploadsMetadata':
				break;
			case 'nFilesToUpload':
				break;
			default:
		}
	}

	/*
	 index
	 uploadsMetadata: structure indexed by filename, fields: datasetName, datasetDesc, datasetdefId, filename
	*/
	public function saveDatafile($index) // Save a single datafile referenced by the 'uploaded' index
	{
		$file = $this->uploads[$index];
		$datasetName = $this->uploadsMetadata[$index]['datasetName'];
		$datasetDesc = array_key_exists('datasetDesc', $this->uploadsMetadata[$index]) ? $this->uploadsMetadata[$index]['datasetDesc'] : '';
		$datasetdefId = $this->uploadsMetadata[$index]['datasetdefId'];
		$fileName = $this->uploadsMetadata[$index]['fileName'];
		$this->debug(2, "Processing upload $index");
		$originalName = $file->getClientOriginalName();
		if("$originalName" == "")
			$this->error('trying to create a datafile with an empty name');
		// create dataset
		if(!Dataset::where('name', "$datasetName")->where('database_id', $this->database->id)->exists())
		{
			$this->debug(1, "Creating dataset");
			$this->setStatus("Creating dataset");
			// create the dataset
			$dataset = new Dataset();
			$dataset->name = $datasetName;
			$dataset->description = $datasetDesc;
			$dataset->database_id = $this->database->id;
			$dataset->save();
		}
		else
		{
			$this->debug(1, "Using existing dataset");
			$this->setStatus("Using existing ");
			$dataset = Dataset::where('name', "$datasetName")->where('database_id', $this->database->id)->first();
		}

		//
		// create datafile
		//

		// check if datafile already exists
		$datafile = Datafile::where('datasetdef_id', $datasetdefId)
			->where('dataset_id', "$dataset->id")
			->first();
		if($datafile)
			$this->debug(1, "A datafile for the datasetdef $datasetdefId already exists in the database (id: $datafile->id)");
		if($datafile && !$this->overwriteExisting)
		{
			$this->debug(1, "Since this overwriting existing datafiles is disabled, we will ignore this upload.");
		}
		else
		{
			// create new Datafile or overwrite
			$existing = false;
			if(!$datafile)
			{
				// create a new datafile
				$this->debug(1, "Creating a new datafile for $originalName");
				$datafile = new Datafile();
				// set mandatory fields
				$datafile->dataset_id = $dataset->id;
				$datafile->datasetdef_id = $datasetdefId;
			}
			else
			{	
				// overwrite existing datafile
				$existing = true;
				$this->debug(1, "Overwrite existing datafile $datafile->id");
			}

			$datafile->name = "$originalName";
			$datafile->mimetype = $file->getMimeType();
			$datafile->save(); // save so datafile has ID (necessary for saving file)

			if($existing)
			{
				$this->debug(1, "Touching datafile to set updated_at");
				$datafile->touch(); // touch the file to reset 'updated_at' and trigger DatafileObserver
			}
			$directory = $datafile->directory();
			$this->dispatch('saving-file', name: $datafile->name); // dispatch a browser event
			$this->dispatch('showFlashMessage', ['type' => 'success', 'message' => 'storeAs']);
				// Save the file to disk (=move from temporary location)
			$file->storeAs("$directory", "$datafile->name", 'sonicom-data');
			//jw:todo add to 'saved' and remove from '$uploads'
			$this->saved[] = $originalName;
		}
		// delete from livewire-tmp, but don't remove from uploads, since otherwise all hell
		// will break loose with the index!
		$file->delete();
	}

	public function render()
	{
		$this->debug(1, 'Livewire render()');
		return view('livewire.database-upload');
	}

	public function redirectToDatasets()
	{
		return redirect()->to("/databases/".$this->database->id."/showdatasets");
	}

	////////////////////////////////////////////////////////////////////////////////
	// PRIVATE
	////////////////////////////////////////////////////////////////////////////////

	private function error($p)
	{
		$this->console("ERROR: $p");
	}

	private function debug($debugLevel, $p, $indent=-1)
	{
		if($indent >= 0 && $indent != $this->debugIndent)
			$this->debugIndent = $indent;
		if($this->debugLevel >= $debugLevel)
			$this->console("DEBUG($debugLevel): ".str_repeat(' ', $this->debugIndent)."$p");
	}

	private function console($p)
	{
		$this->js("console.log('$p');");
	}

	/*
	 * Update array with list of existing datafiles with original file names
	 *
	 * jw:todo jw:note If the filter has changed, then the file name of an existing file may differ from a potential upload for this datafile!
	 */
	public function calculateExisting() : int
	{
		$this->database = $this->database->fresh(['datasetdefs', 'datasets.datafiles']);
		$existingFilesMetadata = [];
		foreach($this->database->datasets as $dataset)
		{ 
			$datasetname = $dataset->name;
			$datasetdescription = $dataset->description;
			foreach($dataset->datafiles as $datafile)
			{				
					// save metadata of existing datafiles so we can compare with pendingUploads and know
					// which ones we actually have to upload!
					// additional metadata for '$uploads': 'datasetName', 'datasetDesc', 'datasetdefId', 'fileName'				
					// Using local variables (instead of $this->) saves processing time. 
				$index = count($existingFilesMetadata);
				$existingFilesMetadata[$index]['datasetName'] = $datasetname;
				$existingFilesMetadata[$index]['datasetDesc'] = $datasetdescription;
				$existingFilesMetadata[$index]['datasetdefId'] = $datafile->datasetdef_id;			
			}
		}
		
		$this->existingFilesMetadata = $existingFilesMetadata;
		$this->nFilesExisting = count($existingFilesMetadata);	
		return $this->nFilesExisting;
	}

	private function setStatus($status)
	{
		$this->status = "$status";
		if($this->debugLevel > 0 )
			$this->console("Status (Livewire): $status");
	}

}
