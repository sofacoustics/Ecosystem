<?php

namespace App\Livewire;

use App\Models\Tool;

use App\Http\Resources\ToolResource;
use App\Mail\ToolDOIAssigned;
use App\Mail\ToolPersistentPublicationRequested;
use App\Services\ToolRadarDatasetBridge;

use Illuminate\Support\Facades\Mail;

use Livewire\Component;

class ToolDoi extends Component
{
	public $tool;
	public $doi;
	public $status; // set messages to be viewed in view
	public $warning;
	public $error; // set error messages to be viewed in view
	public $radar_status; // null or 0: nothing happened with RADAR yet; 1: DOI assigned; 2: Requested publication (started) 3: Requested publication (finished), curator notified; 4: Database persistently published.


	protected $rules = [
	];

	public function mount(Tool $tool)
	{
		if($tool) 
		{
			$this->tool = $tool;
			$this->doi = $tool->doi;
			if ($tool->radar_status == null)
				$this->radar_status = 0;
			else
				$this->radar_status = $tool->radar_status;
		}
		else
		{
			// throw some error here
		}
	}

	public function assignDOI()
	{	
		$start = microtime(true);
		$this->dispatch('status-message', 'Starting DOI assignment');
		$this->error = "";
		$this->warning = "";

		$radar = new ToolRadarDatasetBridge($this->tool);
			// create RADAR dataset
		if(!$this->tool->radar_id)
		{ 
			if($radar->create())
			{
				$this->dispatch('radar-status-changed', 'RADAR Dataset created'); // let other livewire components know the radar status has changed
				$this->dispatch('status-message', $radar->message);
				$content_created = $radar->content;
			}
			else
			{
				$this->dispatch('status-message', $radar->message);
				$this->error = $radar->message.' RADAR Message: '.$radar->details.' *** Content created: *** '.json_encode($radar->content);
				$this->radar_status = $this->tool->radar_status;
				return;
			}
		}	// validate metadata
		else if(!$radar->metadataValidate())
		{
			$this->dispatch('radar-status-changed', 'Validation failed');
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			$this->radar_status = $this->tool->radar_status;
			return;
		}

		if($radar->read())
		{
			$state = $radar?->radar_dataset?->state ?? 'INVALID RADAR STATE';
			if($state  == "PENDING")
			{		// we need to start the review process in order to get the DOI
				if(!$radar->startreview())
				{
					$this->error = $radar->message.' RADAR Message: '.$radar->details;
					$this->radar_status = $this->tool->radar_status;
					return;
				}
			}
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			$this->radar_status = $this->tool->radar_status;
			return;
		}
		// retrieve doi
		if($radar->getDOI())
		{
			$this->doi = $radar->doi; 
			$this->dispatch('status-message', $radar->message);
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			return;
		}
			// stop review process
		if(!$radar->endreview())
		{
			$this->error = $radar->details;
			return;
		}
		
		$this->tool->radar_status = 1;
		$this->tool->doi = $this->doi;
		$this->tool->save();
		app('log')->info('DOI assigned to tool', [
			'feature' => 'tool-radar-dataset',
			'tool_id' => $this->tool->id,
			'user_id' => auth()->user()->id,
			'target_url' => config('services.radar.baseurl'),
			'duration' => microtime(true) - $start
		]);
		$adminEmails = config('mail.to.admins');
		Mail::to(explode(',',$adminEmails))->queue(new ToolDOIAssigned($this->tool));
		app('log')->info("Sending toolDOIAssigned email to $adminEmails", [
			'feature' => 'tool-radar-dataset',
			'tool_id' => $this->tool->id,
			'user_id' => auth()->user()->id,
			'target_url' => config('services.radar.baseurl'),
			'duration' => microtime(true) - $start
		]);
		$this->radar_status = $this->tool->radar_status;
	}

	public function submitToPublish()
	{
		$this->error = "";
		$this->warning = "";

		$radar = new ToolRadarDatasetBridge($this->tool);
		if($radar->update())
		{
			$this->dispatch('radar-status-changed', 'Dataset updated'); // let other livewire components know the radar status has changed
			$this->dispatch('status-message', $radar->message);
			$content_created = $radar->content;
		}
		else
		{
			$this->dispatch('status-message', $radar->message);
			$this->error = $radar->message.' RADAR Message: '.$radar->details.' *** Content updated: *** '.json_encode($radar->content);
			return;
		}
			// validate metadata
		if(!$radar->metadataValidate())
		{
			$this->dispatch('radar-status-changed', 'Validation failed');
			$this->error = $radar->message.' RADAR Message: '.$radar->details.' *** Content created: *** '.json_encode($content_created);
			return;
		}
		// upload tool to the Datathek
		if(!$radar->upload())
		{
			$this->error = $radar->message.' ('.$radar->details.')';
			return;
		}
		else
		{
			$this->dispatch('status-message', $radar->message);
			// Starting the 'review' process directly after uploading was failing.
			// sleeping for 5 seconds appears to fix this. Not a pretty solution though!
			// help when starting the review directly after uploading files, which otherwise fails!
			sleep(5);
		}

		// after the upload, we can trigger the review
		if(!$radar->startreview())
		{
			$this->error = $radar->message.' RADAR Review Message: '.$radar->details;
			return;
		}
		else
		{
			$adminEmails = config('mail.to.admins');
			Mail::to(explode(',',$adminEmails))->queue(new ToolPersistentPublicationRequested($this->tool));
			app('log')->info('Persistent publication requested', [
				'feature' => 'tool-radar-dataset',
				'tool_id' => $this->tool->id,
				'target_url' => config('services.radar.baseurl'),
				'emails' => $adminEmails
			]);
			$this->dispatch('status-message', $radar->message);
		}

		$this->tool->radar_status=3; // note that until we use a job to upload the tool, we skip '2'.
		$this->tool->save();
		$this->radar_status = $this->tool->radar_status;
		$this->dispatch('status-message', 'Tool has been successfully submitted for persistent publication!');
		//$this->js('window.location.reload()'); //jw:note I think this refreshes page removing status messages too early and is unnecessary
	}

	public function render()
	{
		return view('livewire.tool-doi');
	}
}
