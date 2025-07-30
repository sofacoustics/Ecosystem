<?php

namespace App\Livewire;

use Livewire\Component;

use App\Mail\ToolPersistentPublicationRejected;
use App\Services\ToolRadarDatasetBridge;
use App\Services\ToolfileRadarFileBridge;

use Illuminate\Support\Facades\Mail;

class ToolRadarActions extends Component
{
	public $tool;

	// RADAR properties
	public $id;
	public $file_id; // the tool's file_radar_id
	public $state;
	public $doi;
	public $size;
	public $radar_content;
	public $isExpanded = false; // Initial state of the RADAR content: collapsed

	// the RADAR state - used to display/hide buttons
	public $pending = false;
	public $review = false;
	public $radar_status = null; // this will be set to the value of the tool field 'radar_status'.
	public $last_retrieved = null;

	// true if we haven't uploaded yet
	public $canUpload = false;

	public $error; // any error message to display


	public function mount($tool)
	{
		$this->tool = $tool;
		$this->radar_status = $tool->radar_status;
		if($tool->radar_id)
		{
			$this->id = $tool->radar_id;
			$this->refreshStatus();
		}
		else
		{
			$this->dispatch('status-message', 'There is no RADAR dataset associated with this tool!');
		}
	}

	public function toggleExpand()
	{
		$this->isExpanded = !$this->isExpanded; // Toggle the boolean value
	}

	// set RADAR state (pending, review, published)
	public function setState($state)
	{
		$this->state = $state;
		if($state == 'PENDING')
			$this->pending = true;
		else
			$this->pending = false;
		if($state == 'REVIEW')
			$this->review = true;
		else
			$this->review = false;
	}

	public function createDataset()
	{
		$radar = new ToolRadarDatasetBridge($this->tool);
		$this->dispatch('status-message', 'Starting RADAR dataset creation process.');
		if($radar->create())
			$this->dispatch('radar-status-changed', 'Dataset created'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function startReview()
	{
		$this->dispatch('status-message', 'Starting review process.');
		$radar = new ToolRadarDatasetBridge($this->tool);
		if($radar->startreview())
			$this->dispatch('radar-status-changed', 'Review process started'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details; // output error message
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function endReview()
	{
		$this->dispatch('status-message', 'Ending review process.');
		$radar = new ToolRadarDatasetBridge($this->tool);
		if($radar->endreview())
			$this->dispatch('radar-status-changed', 'Review process ended'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function uploadToRadar() // note that the 'upload()' function is reserved in Livewire
	{
		$this->error = "";
		$this->dispatch('status-message', 'Starting upload.');
		$radar = new ToolRadarDatasetBridge($this->tool);
		if(!$radar->upload())
			$this->error = $radar->message.' ('.$radar->details.')';
		$this->dispatch('status-message', $radar->message);
	}

	/*
	* This will delete the Tool from the Datathek and remove the DOI in the Ecosystem
	*/
	public function deleteFromRadar()
	{
		$this->dispatch('status-message', 'Starting to delete the RADAR dataset');
		$radar = new ToolRadarDatasetBridge($this->tool);
		if($radar->delete())
		{
			$this->id = null;
			$this->dispatch('radar-status-changed', 'The tool has been deleted'); // let other livewire components know the radar status has changed
		}
		else
			$this->error = $radar->details;


		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	/* 
	* Approve the persistent publication: Set the Status to "Persistently published". 
	*
	* Note: nothing happens at the Datathek currently. 
	*/
	public function approvePersistentPublication() 
	{
		$this->reset('error');
		$radar = new ToolRadarDatasetBridge($this->tool);
		if($radar->publish())
			$this->dispatch('radar-status-changed', 'Tool published'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details; // output error message
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	/*
	* Reject the persistent publication: End the review at Datathek and set the Status to "DOI assigned"
	*/
	public function rejectPersistentPublication()
	{
		// end the review
		$start = microtime(true);
		$radar = new ToolRadarDatasetBridge($this->tool);
		if(!$radar->endreview())
		{
			$this->error = 'End Review: '.$radar->message . ' ('.$radar->details .')';
			return;
		}
		else
		{
			app('log')->notice('Rejecting persistent publication', [
				'feature' => 'tool-radar-dataset',
				'tool_id' => $this->tool->id,
				'user_id' => auth()->user()->id,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start
			]);
			// send user and admin an email
			$adminEmails = config('mail.to.admins');
			$userEmail = $this->tool->user->email;
			$recipients = $userEmail . ',' . $adminEmails;
			Mail::to(explode(',',$recipients))->queue(new ToolPersistentPublicationRejected($this->tool));
			app('log')->info("ToolPersistentPublicationRejected email for tool " . $this->tool->title . " (" . $this->tool->id . ") sent to $recipients");
		}
		// set status to "DOI assigned"
		$this->tool->radar_status = 1;
		$this->tool->save();

		$this->refreshStatus();
	}

	/*
	* Remove the DOI from the Ecosystem and all links to the Datathek. But it does nothing in the Datathek.
	*/
	public function resetDOI()
	{	
		$this->tool->radar_id = null;
		$this->tool->radar_upload_url = null;
		$this->tool->doi = null;
		$this->tool->radar_status = null;
		$this->tool->save();

		$this->refreshStatus();
	}

	public function render()
	{
		return view('livewire.tool-radar-actions');
	}

	public function validateMetadata()
	{
		$this->error = null;
		$radar = new ToolRadarDatasetBridge($this->tool);
		if(!$radar->metadataValidate())
		{
			$this->error = "Failed to validate: $radar->details";
			return false;
		}
		else
			$this->dispatch('status-message', 'RADAR metadata validation successful!');
	}

	public function refreshStatus()
    {
		$radar = new ToolRadarDatasetBridge($this->tool);
		if($radar->read())
		{
			$this->id = $radar?->radar_dataset?->id ?? null;
			$this->file_id = $this->tool->file_radar_id; //jw:todo get this from RADAR?
			$this->state = $radar->radar_dataset->state;
			$this->doi = $radar?->radar_dataset?->descriptiveMetadata?->identifier?->value ?? null;
			$this->size = $radar?->radar_dataset?->technicalMetadata?->size ?? "unknown";
			$this->radar_content = $radar->radar_content;
			$this->canUpload = true;
			$this->setState($radar?->radar_dataset?->state ?? '');
			$this->last_retrieved = now();
		}
		else
		{
			if($this->tool->radar_id)
				$this->error = $radar->message;
			$this->id = null;
			$this->state = null;
			$this->doi = null;
			$this->size = null;
			$this->radar_content = null;
			$this->canUpload = false;
			$this->setState('');
		}
		$this->radar_status = $this->tool->radar_status;
	}
}
