<?php

namespace App\Livewire;

use App\Mail\DOIAssigned;
use App\Mail\PersistentPublicationRequested;
use App\Models\Database;

use App\Http\Resources\DatabaseResource;
use App\Services\DatabaseRadarDatasetBridge;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Livewire\Component;

/*
 * https://neon.tech/guides/laravel-livewire-blog#implementing-the-blog-functionality
 */
class DatabaseVisibility extends Component
{
	public $database;
	public $visible;
	public $doi;
	public $status; // set messages to be viewed in view
	public $warning;
	public $error; // set error messages to be viewed in view
	public $radar_status; // null or 0: nothing happened with RADAR yet; 1: DOI assigned; 2: Requested publication, curator notified; 3: Database persistently published.

	protected $rules = [
	];

	public function mount(Database $database)
	{
		if($database) 
		{
			$this->database = $database;
			$this->visible = $database->visible;
			$this->doi = $database->doi;
			if ($database->radar_status == null)
				$this->radar_status = 0;
			else
				$this->radar_status = $database->radar_status;
		}
	}

	public function expose()
	{
		$this->database->visible = true;
		$this->database->save();
		$this->visible = $this->database->visible;
		$this->js('window.location.reload()'); 
	}

	public function hide()
	{
		$this->database->visible = false;
		$this->database->save();
		$this->visible = $this->database->visible;
		$this->js('window.location.reload()'); 
	}

	public function assignDOI()
	{
		$this->dispatch('status-message', 'Starting DOI assignment');
		$this->error = "";
		$this->warning = "";

		$radar = new DatabaseRadarDatasetBridge($this->database);
			// create RADAR dataset
		if(!$this->database->radar_id)
		{
			if($radar->create())
			{
				$this->dispatch('radar-status-changed', 'Dataset created'); // let other livewire components know the radar status has changed
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
			$state = $radar?->radar_dataset?->state ?? 'INVALID RADAR STATE';
			if($state  == "PENDING")
			{
				// we need to start the review process in order to get the DOI
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
		if($radar->getDOI())
		{
			$this->doi = $radar->doi; 
			$this->dispatch('status-message', $radar->message);
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
		}
		// stop review process
		if(!$radar->endreview())
			$this->error = $radar->details;
		
		$this->database->radar_status = 1;
		$this->database->doi = $this->doi;
		$this->database->save();
		$adminEmails = config('mail.to.admins'); 
		Mail::to(explode(',',$adminEmails))->send(new DOIAssigned($this->database));
		app('log')->info("DOI assigned for database " . $this->database->title . " (" . $this->database->id . "). Sending DOIAssigned email to $adminEmails");
		$this->radar_status = $this->database->radar_status;
	}

	public function submitToPublish()
	{
		//$this->dispatch('status-message', 'Updating metadata');
		$this->error = "";
		$this->warning = "";

		$radar = new DatabaseRadarDatasetBridge($this->database);
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
		// upload database to the Datathek
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
			$this->dispatch('status-message', $radar->message);
		}

		$this->database->radar_status=2;
		$this->database->save();
		$adminEmails = config('mail.to.admins');
		Mail::to(explode(',',$adminEmails))->send(new PersistentPublicationRequested($this->database));
		app('log')->info("Persistent publication requested for " . $this->database->title . " (" . $this->database->id . "). Sending PersistentPublicationRequested email to $adminEmails");
		$this->radar_status = $this->database->radar_status;
		$this->dispatch('status-message', 'The database has been successfully published!');
		//$this->js('window.location.reload()'); 
		return redirect()->route('databases.show', $this->database);
	}

	public function render()
	{
		return view('livewire.database-visibility');
	}
}
