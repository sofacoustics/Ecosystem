<?php

namespace App\Livewire;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Validator;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

use App\Models\Tool;

class ToolUpload extends Component
{
	use WithFileUploads; // trait necessary for livewire upload

	// wire:model
	public $file;

	// component parameter (:tool) if editing existing tool
	public Tool $tool;

	public function mount()
	{
		\Log::info("Tool::mount()");
	}


	#[On('livewire-upload-cancel')]
	public function handleCancelUpload()
	{
			// Handle the cancellation
		\Log::info("ToolUpload::handleCancelUpload()");
		//dd('handleCancelUpload()');
			// For example, reset any related properties or perform cleanup
	}

	public function updatedFile()
	{
		//dd(true);
		\Log::info("updatedFile()");
		try 
		{
			$this->save();
		} 
		catch (\Illuminate\Validation\ValidationException $e) 
		{
			\Log::info("validation failed");
			//dd($e);
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

    protected function rules()
    {
			return [];
    }

	public function save()
	{
		if($this->tool->radar_status > 1)
		{
			app('log')->error("ToolUpload::save() - attempting to save a new file when persistent publication has already been requested!");
			if($this->tool->radar_status == 2)
				$message = "Uploading a new file is not permitted since persistent publication for this tool has been requested!";
			else if($this->tool->radar_status == 3)
				$message = "Uploading a new file is not permitted since this tool has been persistently published!";
			else
				$message = "Uploading a new file is not permitted since the radar_status is greater than 1!";
			$this->error = $message;
			$this->addError('file', $message); // error messages weren't appearing without this, when using try/catch!
			return;
		}
		if(!$this->file)
		{
			app('log')->debug("ToolUpload::save() - $this->file is empty, so returning early!");
			return;
		}
		app('log')->debug("ToolUpload::save()");

		$this->authorize('update', $this->tool); // check if tool can be modified
		$this->tool->removefile(); // if there was already a file --> remove the old one
		$this->tool->filename = $this->file->getClientOriginalName();
		$this->tool->save(); // save the tool filename
		$filename = $this->file->getClientOriginalName();
		$directory = $this->tool->directory();
		$this->dispatch('showFlashMessage', ['type' => 'success', 'message' => 'storeAs']);
		$this->file->storeAs("$directory", "$filename", 'sonicom-data');
		// clean up
		$this->file->delete();
		$this->redirect(route('tools.show', $this->tool));
	}

	public function render()
	{
			return view('livewire.tool-upload');
	}
}
