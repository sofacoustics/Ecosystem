<?php

namespace App\Livewire;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

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

	protected function rules()
	{
		$requiredMimetypes = $this->datasetdef->datafiletype->mimetypes;
		if($requiredMimetypes != "")
			$requiredMimetypes="|mimetypes:$requiredMimetypes";

		return [
			'file' => "required$requiredMimetypes",
		];
	}

	public function mount()
	{		//jw:note doesn't appear to work. $this->authorize('create', $this->dataset);
		\Log::info("DatafileUpload::mount()");
	}

	#[On('livewire-upload-cancel')]
	public function handleCancelUpload()
	{	// Handle the cancellation
		\Log::info("DatafileUpload::handleCancelUpload()");
		// For example, reset any related properties or perform cleanup
	}

	public function updatedFile()
	{
		\Log::info("updatedFile()");
		try 
		{
			$this->validate($this->rules());
			\Log::info("validation passed");
			$this->save();
		} 
		catch (\Illuminate\Validation\ValidationException $e) 
		{
			\Log::info("validation failed");
			\Log::info("deleting file");
			$this->file->delete(); // deletes the temporary file
			\Log::info("resetting 'file'");
			$this->reset('file');
			\Log::info("Setting 'file' to null");
			$this->file = null;
			\Log::info("Setting custom validation error message");
			$this->addError('file', $e->getMessage()); // error messages weren't appearing without this, when using try/catch!
		}
	}

	public function save()
	{
		if(!$this->file)
		{
			\Log::info("DatafileUpload::save() - $this->file is empty, so returning early!");
			return;
		}
		\Log::info("DatafileUpload::save()");
				// https://www.iana.org/assignments/media-types/media-types.xhtml#audio
		$requiredMimetypes = $this->datasetdef->datafiletype->mimetypes;
		\Log::info("Required MIME type: {$requiredMimetypes}");
		$mimetype = $this->file->getMimeType();
		\Log::info("Uploaded file MIME type: {$mimetype}");

		$this->validate();
		$this->authorize('update', $this->dataset); // check if dataset can be modified

			// if datafile doesn't exist, create it here!
		if (!isset($this->datafile)) 
		{		// create new Datafile
			$datafile = new Datafile();
				// set mandatory fields
			$datafile->dataset_id = $this->dataset->id;
			$datafile->datasetdef_id = $this->datasetdef->id;
		} 
		else 
		{ 	// remove old files when editing existing file
			$this->datafile->clean(); //jw:todo
		}
		$datafile->name = $this->file->getClientOriginalName();
		//session()->flash('status', "Datafile (id=$datafile->id) is being saved to the database");
		$datafile->save(); // save so datafile has ID (necessary for saving file)
		$datafile->dataset->touch(); // change updated_at of the dataset
		$datafile->dataset->database->touch();  // change updated_at of the database
		
		$directory = $datafile->directory();
		$this->dispatch('showFlashMessage', ['type' => 'success', 'message' => 'storeAs']);
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
