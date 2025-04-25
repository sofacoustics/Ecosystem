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
		public $datasetdescriptionfilter;
    public $datafilenamefilters= [];
    public $datasetsCount = 0;

    public bool $started = false;
    public bool $cancelled = false;
    public bool $finished = false;
    public $status = "";

    public $directory;

    public array $uploads;  // files uploaded to livewire-tmp

    public array $pending;  // a list of names of the files which are filtered and neither exist, nor have been uploaded (pending upload)
    public array $existing; // a list of names of the datafiles which already exists
    public array $saved;    // a list of names of the files which have been saved to the database;
    public array $filtered; // a list of names of the files which fit the filter criteria
    public array $uploaded; // a list of names of the files which have been uploaded

    public $overwriteExisting = false; // set to true, if existing datafiles should be overwritten.

    public array $pdatasetnames= []; // array of dataset names, e.g. NH01
    public array $fdatafilenames= []; // a 2D array with filtered datafilenames, dim 1: index of pdatasetnames, dim 2: index of datasetdefIds
    public array $pdatafilenames= []; // a 2D array with selected (=a subset of filtered) datafilenames, dim 1: index of pdatasetnames, dim 2: index of datasetdefIds
		public $dirMode = 0;
		
    public $progress;
    public $uploading;

    public $nFilesInDir = -1; // -1: directory not selected yet, 0: no files in the directory, >0: files found
    public $nFilesFiltered = 0;
    public $nFilesToUpload = 0;
    public $nFilesUploaded = 0;

    public bool $canUpload = false; // set to true, if there are filtered files we can upload

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

    public function cancel()
    {
        dd('cancel()');
        $this->cancelled = true;
        $this->started = false;
        //jw:todo cleanup
    }
    public function cancelUpload($file)
    {
        dd($file);
    }

    public function resetUploads()
    {
        $this->setStatus("Resetting uploads");
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
            }
            else
            {
                //$this->console("resetUpload(): skipping $originalName");
            }
        }
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
        $this->calculateUploaded();
    }


    public function setDTO($dto)
    {
        //$this->dto = $dto;
        //dd($dto);
    }

    public function fileList()
    {
        dd('fileList()');
    }


    //
    // Updated
    //
    public function updatedDirectory()
    {
        dd('updatedDirectory');
        if ($this->directory) {
            foreach ($this->directory as $file) {
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
        $this->calculatePending();
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
        $this->nFilesFiltered = $nDatafiles; // number of datafiles minus number of datasets
        $this->setStatus("\$this->pdatafilenames set to $this->nFilesFiltered entries");
    }

    // https://dev.to/zaxwebs/accessing-updated-index-in-livewire-48oc
    public function updatedDatafilenamefilters($value, $key)
    {
        //jw:todo check syntax etc.
        //
        //
        //$value = filter_var($value, FILTER_SANITIZE_STRING); // https://www.geeksforgeeks.org/how-to-validate-and-sanitize-user-input-with-php/
        $value = $this->sanitizePattern($value); // remove invalid characters
        //FILTER_SANITIZE_STRING is deprecated
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
       // dd($field, ' updated');
        //$this->console("updated($property, $value) key: $key");
        // save to database
        if("$property" == "datasetnamefilter")
        {
            //jw:todo filter
            $this->database->bulk_upload_dataset_name_filter = $this->sanitizePattern($this->datasetnamefilter);
			$this->database->save();
        }
        else if("$property" == "uploads")
        {
            // $field = e.g. "uploads.0"
            // $value = e.g. [
            //   "fileName" => "D8_48K_24bit_256tap_FIR_SOFA.sofa"
            //   "fileSize" => 11656471
            //   "progress" => 0
            // ]
            //if(array_key_exists('fileRef', array($value)))
            //    dd("fileRef exists");
            //dd("$field, $value, $property, $key");
            // Only update uploads if the fileRef value has been set,
            // since the others are set first.
            $this->calculateUploaded();
            $this->calculatePending();
        }
        else if("$property" == "filtered")
        {
            dd("$property updated");
            $this->console('filtered['.$key.'] updated');
            sort($this->filtered);
            $this->calculatePending();
            $this->saved = [];
        }
        else if("$property" == "nFilesToUpload")
        {
            $this->setStatus("Updating nFilesToUpload to $value");
        }
        else
        {
            //dd("$field: $value");
        }
    }

    public function updating($property)
    {
        //dd("updating $property");
    }

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
                if(!Dataset::where('name', "$datasetname")->exists())
                {
                    $this->debug(1, "Creating dataset");
                    // create the dataset
                    $dataset = new Dataset();
                    $dataset->name = "$datasetname";
                    $dataset->description = "Bulk uploaded dataset";
                    $dataset->database_id = $this->database->id;
                    $dataset->save();
                }
                else
                {
                    $this->debug(1, "Using existing dataset");
                    $dataset = Dataset::where('name', "$datasetname")->first();
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

                    //jw:todo find out which datafile
                    //dd($this->pdatafilenames);
                    $this->debug(2, "Checking if datasetnameKey $datasetnameKey exists in pdatafilenames");
                    if(array_key_exists($datasetnameKey, $this->pdatafilenames))
                    {
                        $this->debug(2, "-> datasetnameKey $datasetnameKey exists in pdatafilenames ($datasetname)");

                        $this->debug(2, "Checking if pdatafilenames[$datasetnameKey] key $datasetdefKey exists.");
                        if(!array_key_exists($datasetdefKey, $this->pdatafilenames[$datasetnameKey]))
                        {
                            // this datasetdef has no corresponding datafilenames entry! continue;
                            $this->debug(2, "pdatafilenames[$datasetnameKey] key $datasetdefKey does not exist.");
                            continue;
                        }
                        $this->debug(2, "pdatafilenames[$datasetnameKey] key $datasetdefKey exists.");
                    }
                    else
                    {
                        $this->debug(2, "pdatafilenames key $datasetnameKey does not exist.");
                        continue;
                    }
                    // the pdatafilenames entries may include nested entries with paths
                    $datafileNameWithPath = $this->pdatafilenames[$datasetnameKey][$datasetdefKey];
                    // if there is a '/' in it
                    if(strpos($datafileNameWithPath, '/') !== false)
                    {
                        // remove relative directory
                        $datafileName = substr($datafileNameWithPath, strrpos($datafileNameWithPath, '/') + 1);
                    }
                    else
                        $datafileName = $datafileNameWithPath;
                    $this->debug(1, "Datafile name to look for in uploads: $datafileName");
                    //
                    foreach($this->uploads as $key => $upload)
                    {
                        //if(!isset($upload['fileRef']))
                        //    continue; // it may be that the fileRef is not set, although, e.g. fileName is. (see Javascript)
                        $file = $upload; //$upload['fileRef'];
                        $this->debug(2, "Processing upload $key");
                        $originalName = $file->getClientOriginalName();
                        if("$originalName" == "")
                            $this->error('trying to create a datafile with an empty name');
                        else if("$originalName" == "$datafileName")
                        {
                            $this->debug(1, "Upload match found (upload key $key)");
                            if($datafile && !$this->overwriteExisting)
                            {
                                $this->debug(1, "Skipping existing datafile $datafile->id since overwriteExisting is false");
                                // do nothing except remove upload at end
                            }
                            else
                            {
                                // create new Datafile
                                $existing = false;
                                if(!$datafile)
                                {
                                    $this->debug(1, "Creating a new datafile for $originalName");
                                    $datafile = new Datafile();
                                    // set mandatory fields
                                    $datafile->dataset_id = $dataset->id;
                                    $datafile->datasetdef_id = $datasetdefId;
                                }
                                else
                                {
                                    $existing = true;
                                    $this->debug(1, "Overwrite existing datafile $datafile->id");
                                }

                                $datafile->name = "$originalName";
                                //session()->flash('status', "Datafile (id=$datafile->id) is being saved to the database");
                                $datafile->save(); // save so datafile has ID (necessary for saving file)
                                if($existing) {
                                    $this->debug(1, "Touching datafile to set 'updated_at'");
                                    $datafile->touch(); // make sure 'updated_at' is set for existing, so DatafileObserver is triggered
                                }
                                $directory = $datafile->directory();
                                $this->dispatch('saving-file', name: $datafile->name); // dispatch a browser event
                                $this->dispatch('showFlashMessage', ['type' => 'success', 'message' => 'storeAs']);
                                //session()->flash('status', "Datafile $datafile->name is being saved to disk");
                                // Save the file to disk (moving from temporary location)
                                $file->storeAs("$directory", "$datafile->name", 'sonicom-data');
                                //$this->validate();
                                //jw:todo add to 'saved' and remove from '$uploads'
                                $this->saved[] = $originalName;
                            }
                            // clean up
                            $this->debug(1, "Deleting temporary uploaded file and removing $key entry from upload list");
                            $file->delete();
                            unset($this->uploads[$key]);
                            $this->nFilesUploaded = count($this->uploads);
                            break;
                        }
                    }
                }
			}
        }
        $this->setStatus("Saving now complete");
        $this->saved = []; // reset saved
        $this->uploaded = []; // reset uploaded
        $this->nFilesToUpload = 0;
        $this->uploading = false;
        $this->refresh();
        $this->debug(1, "save(): finished", 0);
        $this->dispatch('saved-to-database');
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
     * calculate the $this->pending property, based on the $this->uploads, $this->existing and $this->filtered properties.
     */
    private function calculatePending()
    {
        //$this->calculateUploaded();
        //$this->calculateExisting();
        $this->pending = array_diff($this->filtered, $this->uploaded);
        if(!$this->overwriteExisting)
            $this->pending = array_diff($this->pending, $this->existing);
        sort($this->pending);
        //$this->console("calculatePending(): ".count($this->pending)." uploads pending (".count($this->filtered)." filtered files, ".count($uploaded)." uploaded files)");
    }

    /*
     * Update array with list of existing datafiles with original file names
     *
     * jw:todo jw:note If the filter has changed, then the file name of an existing file may differ from a potential upload for this datafile!
     */
    private function calculateExisting()
    {
        $this->existing = [];
        $this->database->load('datasets');
        foreach($this->database->datasets as $dataset)
        {
            foreach($dataset->datafiles as $datafile)
            {
                $this->existing[] = $datafile->name;
            }
        }
        sort($this->existing);
    }

    /*
     * Update array with list of uploaded original file names
     */
    private function calculateUploaded()
    {
        $this->uploaded = [];
        foreach($this->uploads as $key => $upload)
        {
            //if(isset($uploaded['fileName']))
            //$this->uploaded[] = $upload['fileName']; //jw:todo could this cause inconsistencies?
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

    private function calculateDatasetCount()
    {
        //$this->database->load('datasets');
        //$this->datasets = $this->database->datasets;
        $this->datasetsCount = count($this->datasets);
        $this->console("Updating datasetsCount to $this->datasetsCount");
    }

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
