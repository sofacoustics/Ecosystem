<?php

namespace App\Livewire;

use App\Models\Tool;

use App\Http\Resources\ToolResource;
use App\Services\ToolRadarDatasetBridge;

use Livewire\Component;

class ToolDoi extends Component
{
	public $tool;
	public $doi;
	public $status; // set messages to be viewed in view
	public $warning;
	public $error; // set error messages to be viewed in view
	public $radar_status;

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
				return;
			}
		}
			// validate metadata
		else if(!$radar->metadataValidate())
		{
			$this->dispatch('radar-status-changed', 'Validation failed');
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			return;
		}
		
		if($radar->read())
		{
			$state = $radar?->dataset?->state ?? 'INVALID RADAR STATE';
			if($state  == "PENDING")
			{		// we need to start the review process in order to get the DOI
				if(!$radar->startreview())
				{
					$this->error = $radar->message.' RADAR Message: '.$radar->details;
					return;
				}
			}
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			return;
		}
			// retrieve doi
		if($radar->doi())
		{
			$this->doi = $this->tool->doi;
			$this->dispatch('status-message', $radar->message);
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
		}
			// stop review process
		if(!$radar->endreview())
			$this->error = $radar->details;
		
		$this->radar_status = $this->tool->radar_status;
	}

	public function submitToPublish()
	{
		$this->tool->radar_status=1;
		$this->tool->save();
		$this->radar_status = $this->tool->radar_status;
		$this->js('window.location.reload()'); 
	}

	public function approve() // Emulate the curator approving the publication at the Datathek
	{
		$this->tool->radar_status=2;
		$this->tool->save();
		$this->radar_status = $this->tool->radar_status;
	}

	public function resetDOI()
	{
		$this->tool->radar_status=null;
		$this->tool->doi = null;
		$this->tool->save();
		$this->doi = $this->tool->doi;
		$this->radar_status = $this->tool->radar_status;
		$this->js('window.location.reload()'); 
	}
	public function render()
	{
		return view('livewire.tool-doi');
	}
}
