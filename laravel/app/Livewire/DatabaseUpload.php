<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

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
	public $datasets;
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

	public array $existing; // a list of names of the datafiles which already exists
	public array $saved;    // a list of names of the files which have been saved to the database;
	public array $filtered; // a list of names of the files which fit the filter criteria
	public array $uploaded; // a list of names of the files which have been uploaded

	public $overwriteExisting = false; // set to true, if existing datafiles should be overwritten.

	public array $dsnFiltered= []; // array with filtered dataset names, e.g. NH01
	public array $descrFiltered= []; // array with filtered descriptions
	public array $dfnFiltered= []; // a 2D array with filtered datafilenames, dim 1: index of pdatasetnames, dim 2: index of datasetdefIds
	public $dirMode = -1; // -1: filter not applied yet, 0: flat directory structure, 1: nested directory structure
	public array $pdatasetnames= []; // array with selected (=subset of dsnFiltered) dataset names
	public array $pdatasetdescriptions= []; // array with selected (=subset of descrFiltered) descriptions
	public array $pdatafilenames= []; // a 2D array with selected (=a subset of dfnFiltered) datafilenames, dim 1: index of pdatasetnames, dim 2: index of datasetdefIds

	public $progress;
	public $uploading;

	public $nFilesFiltered = 0;
	public $nFilesExisting = 0; // The number of datafiles which already exist in the database (jw:todo not sure if this works with pending files resulting from filtering)
	public $nFilesToUpload = 0; // The number of files to upload. This is set in the javascript function 'doUpload'
	//public $nFilesUploaded = 0;
	public $nDatasetsSelected = 0; // Number of selected datasets (=subset of filtered datasets)
	public $nFilesSelected = 0; // Number of selected datafiles if uploaded

	public bool $canUpload = false; // set to true, if there are filtered files we can upload jw:todo maybe use nFilesToUpload instead!

	private $debugLevel = 0;
	private $debugIndent = 0;

	public $dto = null; // a DataTranfer object containing list of files to upload

	public function mount(Database $database)
	{
		$this->database = $database->load('datasetdefs','datasets'); // https://laracasts.com/discuss/channels/livewire/livewire-wiremodel-with-model-relationship
		$this->datasetdefIds = $this->database->datasetdefs->pluck('id');
		$this->datasets = $this->database->datasets;
		$this->datasetsCount = count($this->datasets);
		$this->datasetnamefilter = $database->bulk_upload_dataset_name_filter;
		$this->descriptionfilter = $database->bulk_upload_description_filter;
		foreach($this->database->datasetdefs as $datasetdef)
		{
			$this->datafilenamefilters[$datasetdef->id] = $datasetdef->bulk_upload_filename_filter;
		}

		$this->overwriteExisting = session()->get('sonicomEcosystemBulkUploadOverwrite') == 1 ? true : false;
		$this->calculateExisting();
		$this->debug(1, "Mounted");
	}

	public function boot()
	{
		$this->calculateExisting();
		$this->debug(1, "calculatingExisting() from boot()");
	}


	public function start()
	{
		$this->started = true;
	}

		// not working yet!
	public function cancel()
	{
		dd('cancel()');
		$this->cancelled = true;
		$this->started = false;
	}
	
		// not working yet!
	public function cancelUpload($file)
	{
		dd($file);
	}

	/*
	 *	Remove all files from disk
	 *	Empty array
	 *	Recalculate existing
	 */
	public function resetUploads()
	{
		$this->setStatus("resetUploads()");
		foreach($this->uploads as $key => $upload)
		{
			$file = $upload; //$upload['fileRef'];
			$this->console("resetUploads(): deleting file (" . $this->uploadsMetadata[$key]['fileName'] . ") from livewire-tmp");
			$file->delete();
			unset($this->uploads[$key]);
			unset($this->uploadsMetadata[$key]); //jw:todo Does the whole array need anulling?
		}
		$this->uploading = false;
		$this->nFilesToUpload = 0;
		$this->calculateExisting();

		return;

		// OLD CODE

		/*

		// clean up uploads
		foreach($this->filtered as $id => $file)
		{
				//$this->console("filtered[$id]: $file");
		}
		foreach($this->uploads as $key => $upload)
		{
			$file = $upload; //$upload['fileRef'];
			$originalName = $file->getClientOriginalName();
				// if file no longer in filter list, then delete
			if(array_search("$originalName", $this->filtered)===false)
			{
				$this->console("resetUploads(): deleting file ($key) $originalName from livewire-tmp");
				$file->delete();
				unset($this->uploads[$key]);
				unset($this->uploadsMetadata[$key]); //jw:todo Does the whole array need anulling?
			}
			else
			{
				//$this->console("resetUpload(): skipping $originalName");
			}
		}
		if(count($this->uploads))
			dd($this->uploads); // we shouldn't get here!
		//jw:todo Do we need to reset the keys??? See line below
		// compact array so uploading new files can be appended to end using an offset of the array count.
		$this->uploads = array_values($this->uploads);
		$uploadList = "";
		ksort($this->uploads);
		foreach($this->uploads as $key => $upload)
		{
			$originalName = $file->getClientOriginalName();
			$uploadList .= "$key ($originalName), ";
		}
		$this->uploading = false;
		$this->nFilesToUpload = 0;
		$this->calculateUploaded();
		$this->calculateExisting();
		 */
	}

		// PM: No idea what is this for
	public function setDTO($dto)
	{
		//$this->dto = $dto;
		//dd($dto);
	}

		// PM: No idea what is this for
	public function fileList()
	{
			dd('fileList()');
	}


		// PM: No idea what is this for
	public function updatedDirectory()
	{
		dd('updatedDirectory');
		if ($this->directory) {
			foreach ($this->directory as $file)
			{
				$this->files[] = $file;
				// Process each file in the directory
				//$file->storeAs('uploads', $file->getClientOriginalName());
				//dd($file->getClientOriginalName());
			}
		}
	}

	public function updatedOverwriteExisting($param)
	{
		session()->put('sonicomEcosystemBulkUploadOverwrite', "$param");
	}

	public function updatedPdatasetnames($value, $key)
	{
		//dd("array[$key] = $value");
		$this->console("updatedPdatasetnames");
	}

	public function updatedPdatafilenames($value, $key)
	{
		$nTotalElements = count($this->pdatafilenames, 1); // count multi-dimensional array
		$nDatasets = count($this->pdatafilenames);
		$nDatafiles = $nTotalElements - $nDatasets;
		if($nDatafiles > 0)
			$this->canUpload = true;
		else
			$this->canUpload = false;
		$this->nFilesFiltered = $nDatafiles; // number of datafiles minus number of datasets
		$this->setStatus("\$this->pdatafilenames set to $this->nFilesFiltered entries");
	}

		// https://dev.to/zaxwebs/accessing-updated-index-in-livewire-48oc
	public function updatedDatafilenamefilters($value, $key)
	{
		$datasetdef = Datasetdef::find($key);
		$datasetdef->bulk_upload_filename_filter = "$value";
		$datasetdef->save();
		$this->datafilenamefilters[$key] = "$value";
	}

		// 
		// General 'updated' function
		//
	public function updated($field, $value)
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
				// $field = e.g. "uploads.0"
				// $value = Livewire file object
				//$this->calculateUploaded();
				break;
			case 'uploadsMetadata':
				break;
			case 'filtered':
				dd("$property updated");
				$this->console('filtered['.$key.'] updated');
				sort($this->filtered);
				$this->saved = [];
				break;
			case 'nFilesToUpload':
				//$this->setStatus("Updating nFilesToUpload to $value");
				break;
			default:
				//dd("$field: $value");
		}
	}

		// PM: No idea what is this for
	public function updating($property)
	{
		//dd("updating $property");
	}

	/*
	public function save()
	{
		$this->setStatus("Saving: there are " . count($this->uploads)." uploads to save");
		if(count($this->uploads))
		{
			// Create the datasets and their datafiles
			$this->debug(1, "There are ".count($this->pdatasetnames)." datasets to save");
			foreach($this->pdatasetnames as $datasetnameKey => $datasetname)
			{
				$this->debug(1, "Dataset $datasetnameKey: $datasetname", 1);
				// Create dataset if it doesn't exist1
				if(!Dataset::where('name', "$datasetname")->where('database_id', $this->database->id)->exists())
				{
					$this->debug(1, "Creating dataset");
					$this->setStatus("Creating dataset");
						// create the dataset
					$dataset = new Dataset();
					$dataset->name = $datasetname;
					$dataset->description = $this->pdatasetdescriptions[$datasetnameKey];
					$dataset->database_id = $this->database->id;
					$dataset->save();
				}
				else
				{
					$this->debug(1, "Using existing dataset");
					$this->setStatus("Using existing ");
					$dataset = Dataset::where('name', "$datasetname")->where('database_id', $this->database->id)->first();
				}

					// create one datafile for each datasetdef
				$this->debug(1, "There are ".count($this->datasetdefIds)." datafiles to set");
				foreach($this->datasetdefIds as $datasetdefKey => $datasetdefId)
				{
					$this->debug(1, "Datasetdef $datasetdefId", 2);
					//jw:todo validate file!!!
					$datafile = Datafile::where('datasetdef_id', $datasetdefId)
						->where('dataset_id', "$dataset->id")
						->first();
					if($datafile)
						$this->debug(1, "A datafile for the datasetdef $datasetdefId already exists in the database (id: $datafile->id)");
					if($datafile && !$this->overwriteExisting)
						$this->debug(1, "Since this overwriting existing datafiles is disabled, we will just remove the corresponding upload, if it exists.");

					$this->debug(2, "Checking if datasetnameKey $datasetnameKey exists in pdatafilenames");
					if(array_key_exists($datasetnameKey, $this->pdatafilenames))
					{
						$this->debug(2, "-> datasetnameKey $datasetnameKey exists in pdatafilenames ($datasetname)");
						$this->debug(2, "Checking if pdatafilenames[$datasetnameKey] key $datasetdefKey exists.");
						if(!array_key_exists($datasetdefKey, $this->pdatafilenames[$datasetnameKey]))
						{		// this datasetdef has no corresponding datafilenames entry! continue;
							$this->debug(2, "pdatafilenames[$datasetnameKey] key $datasetdefKey does not exist.");
							continue; // pdatafilenames[$datasetnameKey] key $datasetdefKey does not exist -> continue with next datasetdefId
						}
						$this->debug(2, "pdatafilenames[$datasetnameKey] key $datasetdefKey exists.");
					}
					else
					{
						$this->debug(2, "pdatafilenames key $datasetnameKey does not exist.");
						continue; // pdatafilenames key $datasetnameKey does not exist -> continue with next datasetdefId
					}
						// the pdatafilenames entries may include nested entries with paths
					$datafileNameWithPath = $this->pdatafilenames[$datasetnameKey][$datasetdefKey];
						// if there is a '/' in it
					if(strpos($datafileNameWithPath, '/') !== false)
					{		// remove relative directory
						$datafileName = substr($datafileNameWithPath, strrpos($datafileNameWithPath, '/') + 1);
					}
					else
						$datafileName = $datafileNameWithPath;
					$this->debug(1, "Datafile name to look for in uploads: $datafileName");

					foreach($this->uploads as $key => $upload)
					{
						$file = $upload; 
						$this->debug(2, "Processing upload $key");
						$originalName = $file->getClientOriginalName();
						if("$originalName" == "")
								$this->error('trying to create a datafile with an empty name');
						else if("$originalName" == "$datafileName")
						{
							$this->debug(1, "Upload match found (upload key $key)");
							if($datafile && !$this->overwriteExisting)
							{		// file already exists, but overwrite not selected -> skip this file
								$this->debug(1, "Skipping existing datafile $datafile->id since overwriteExisting is false");
							}
							else
							{		// create new Datafile or overwrite
								$existing = false;
								if(!$datafile)
								{		// create a new datafile
									$this->debug(1, "Creating a new datafile for $originalName");
									$datafile = new Datafile();
										// set mandatory fields
									$datafile->dataset_id = $dataset->id;
									$datafile->datasetdef_id = $datasetdefId;
								}
								else
								{		// overwrite existing datafile
									$existing = true;
									$this->debug(1, "Overwrite existing datafile $datafile->id");
								}

								$datafile->name = "$originalName";
								$datafile->mimetype = $file->getMimeType();
								$datafile->save(); // save so datafile has ID (necessary for saving file)
								
								if($existing)
								{
									$this->debug(1, "Touching datafile to set 'updated_at'");
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
								// clean up
							$this->debug(1, "Deleting temporary uploaded file and removing $key entry from upload list");
							$file->delete();
							unset($this->uploads[$key]);
							$this->nFilesUploaded = count($this->uploads);
							break;
						} // if filename is good
					} // foreach datafile
				} // foreach dataset def
			} // foreach dataset
		} // if upload>0

		$this->setStatus("Saving now complete");
		$this->saved = []; // reset saved
		$this->uploaded = []; // reset uploaded
		$this->nFilesToUpload = 0;
		$this->uploading = false;
		$this->refresh();
		$this->debug(1, "save(): finished", 0);
		$this->dispatch('saved-to-database');
		$this->dispatch('status-message', 'Files successfully saved to database');
		$this->redirect('/databases/' . $this->database->id . '/showdatasets');
	}
	 */

	/*
	 * Save a single datafile referenced by the 'uploaded' index
	 */
	public function saveDatafile($index)
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
				$this->debug(1, "Touching datafile to set 'updated_at'");
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

	public function resetDatasets()
	{
		$title = $this->database->title;
		$this->console("Deleting all datasets in the database $title");
		// remove all datasets
		foreach($this->datasets as $dataset)
		{
			$this->console("Deleting $dataset->name");
			$dataset->delete();
		}
		$this->refresh();
	}

	////////////////////////////////////////////////////////////////////////////////
	// PRIVATE
	////////////////////////////////////////////////////////////////////////////////

	private function refresh()
	{
		$this->database->refresh();
		$this->datasets = $this->database->datasets;
		$this->calculateDatasetCount();
		$this->calculateExisting();
	}

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
		$this->existing = [];
		$this->database = $this->database->fresh(['datasetdefs', 'datasets.datafiles']);
		//$this->database->load('datasets');
		$this->existingFilesMetadata = [];
		foreach($this->database->datasets as $dataset)
		{
			foreach($dataset->datafiles as $datafile) 
			{
				$this->existing[] = $datafile->name;
				// save metadata of existing datafiles so we can compare with pendingUploads and know
				// which ones we actually have to upload!
				// additional metadata for '$uploads': 'datasetName', 'datasetDesc', 'datasetdefId', 'fileName'
				$index = count($this->existingFilesMetadata);
				$this->existingFilesMetadata[$index]['datasetName'] = $dataset->name;
				$this->existingFilesMetadata[$index]['datasetDesc'] = $dataset->description;
				$this->existingFilesMetadata[$index]['datasetdefId'] = $datafile->datasetdef->id;
			}
		}
		sort($this->existing);
		$this->nFilesExisting = count($this->existingFilesMetadata);
		return $this->nFilesExisting;
	}

	/*
	 * Update array with list of uploaded original file names
	 */
	/*
	private function calculateUploaded()
	{
		$this->uploaded = [];
		foreach($this->uploads as $key => $upload) {
			$this->uploaded[] = $upload->getClientOriginalName();
		}

		$this->nFilesUploaded = count($this->uploaded);
		$this->debug(1, "calculateUploaded()");
		if($this->uploading && $this->nFilesUploaded == $this->nFilesToUpload)
		{
			$this->setStatus("$this->nFilesUploaded now in \$this->uploads. Resetting \$this->uploading to false");
			$this->uploading = false;
		}
		sort($this->uploaded);
	}
	 */

	private function calculateDatasetCount()
	{
		//$this->database->load('datasets');
		//$this->datasets = $this->database->datasets;
		$this->datasetsCount = count($this->datasets);
		$this->console("Updating datasetsCount to $this->datasetsCount");
	}

	// PM: Unused
	private function sanitizePattern($input)
	{
		return $input; //jw:tmp currently do nothing!
		// Define the pattern to match any character that is not alphanumeric, a period, or < or >
		$pattern = '/[\/:*?"|]/';
		//$pattern = '/[^a-zA-Z0-9.<>]/';
		// Replace any character that matches the pattern with an empty string
		$sanitized = preg_replace($pattern, '', $input);
		return $sanitized;
	}

	private function setStatus($status)
	{
		$this->status = "$status";
		$this->console("Status (Livewire): $status");
	}

}
