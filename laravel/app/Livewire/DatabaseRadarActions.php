<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

use App\Actions\DatabaseDispatchDatasetsInBatchToRadar;
use App\Actions\DatabaseStopPublishingToRadar;
use App\Events\DatabasePersistentPublicationApproved;
use App\Events\DatabasePersistentPublicationRejected;
use App\Jobs\DatabaseEmptyRadar;
use App\Jobs\DatabasePublishToRadar;
use App\Jobs\DatabasePublishInBatchToRadar;
use App\Models\Datafile;
use App\Models\Dataset;
use App\Models\User;
use App\Services\DatabaseRadarDatasetBridge;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DatabaseRadarActions extends Component
{
	public $database;

	// RADAR properties
	public $id;
	public $state;
	public $doi;
	public $size;
	public $radar_content;
	public $isExpanded = false; // Initial state of the RADAR content: collapsed

	// the RADAR state - used to display/hide buttons
	public $pending = false;
	public $review = false;
	public $radar_status = null; // this will be set to the value of the database field 'radar_status'.
	public bool $publishing = false; // true if an upload batch or publish job of this database is open (shows "Stop publishing")
	public bool $emptying = false; // true if a DatabaseEmptyRadar job of this database is queued or running
	// copy progress while publishing (radar_status 2), null otherwise
	public ?int $copiedDatasets = null; // datasets with RADAR folder and all datafiles uploaded
	public ?int $totalDatasets = null;
	public $last_retrieved = null;

	// true if we haven't uploaded yet
	public $canUpload = false;

	public $error; // any error message to display

	#[On('echo:sonicom-ecosystem,.database-published-awaiting-approval')]
	public function handleDatabasePublishedAwaitingApproval($event)
	{
		// Access the 'id' from the broadcast payload
		$id = $event['id'] ?? null; 
		if($id == $this->database->id) {
			$this->refreshStatus(); // refresh if this database has been published and is awaiting approval
		}
	}

	public function mount($database)
	{
		$this->database = $database;
		$this->radar_status = $database->radar_status;
		if($database->radar_id)
		{
			$this->id = $database->radar_id;
			$this->refreshStatus();
		}
		else
		{
			$this->dispatch('status-message', 'There is no RADAR dataset associated with this database!');
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
		$this->reset('error');
		$radar = new DatabaseRadarDatasetBridge($this->database);
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
		$this->reset('error');
		$this->dispatch('status-message', 'Starting review process.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->startreview())
			$this->dispatch('radar-status-changed', 'Review process started'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details; // output error message
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function endReview()
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Ending review process.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->endreview())
			$this->dispatch('radar-status-changed', 'Review process ended'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function uploadToRadar() // note that the 'upload()' function is reserved in Livewire
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Starting upload.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->upload())
			$this->error = $radar->message.' ('.$radar->details.')';
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	/*
	 * Upload the database in batches to RADAR
	 */
	public function uploadToRadarInBatches()
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Starting upload to radar in batches.');
		$userId = auth()->user()->id;

		app(DatabaseDispatchDatasetsInBatchToRadar::class)->handle($this->database, $userId, false); // 'false' means we don't test 'review' here (separate test function).

		$this->refreshStatus();
	}

	public function publishToRadar()
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Starting upload to RADAR via job.');
		DatabasePublishToRadar::dispatch($this->database, auth()->user());
	}

	public function publishInBatchToRadar()
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Starting upload to RADAR via batch job.');
		DatabasePublishInBatchToRadar::dispatch($this->database, auth()->user());
	}

	/*
		If there is an error uploading, we can use this function to empty the RADAR
		dataset, but keep the metadata and the DOI
	*/
	public function emptyDataset()
	{
		$this->reset('error');
		if(!auth()->user()->hasRole('admin'))
		{
			$this->error = "Your user is not allowed to use this function";
			return;
		}
		if(app(DatabaseStopPublishingToRadar::class)->isEmptying($this->database))
		{
			$this->error = 'Emptying the RADAR dataset is already running.';
			return;
		}
		// in the background: one RADAR request per dataset (about 1 s each)
		$n = $this->database->datasets()->whereNotNull('radar_id')->count();
		DatabaseEmptyRadar::dispatch($this->database, auth()->user());
		$this->dispatch('status-message', "Emptying the RADAR dataset ($n folders) has started in the background. This takes about 1 s per folder. The progress is logged; use 'Refresh Status' to check.");
		$this->refreshStatus();
	}

	/*
	 * Stop a running "Publish with DOI" (radar_status 2): cancel the upload batch, delete the
	 * queued jobs and reset radar_status to 1. Uploaded files are kept, so publishing again
	 * continues where it stopped. Use "Empty RADAR dataset" afterwards to remove them.
	 */
	public function stopPublishing()
	{
		$this->reset('error');
		if(!auth()->user()->hasRole('admin'))
		{
			$this->error = "Your user is not allowed to use this function";
			return;
		}

		$action = app(DatabaseStopPublishingToRadar::class);
		if(!$action->handle($this->database, auth()->user()))
			$this->error = $action->message;
		$this->dispatch('status-message', $action->message);
		$this->dispatch('radar-status-changed', 'Publishing stopped'); // let other livewire components know the radar status has changed
		$this->database->refresh();
		$this->refreshStatus();
	}

	/*
	* This will delete the Database from the Datathek and remove the DOI in the Ecosystem
	*/
	public function deleteFromRadar()
	{
		$this->reset('error');
		if(!auth()->user()->hasRole('admin'))
		{
			$this->error = "Your user is not allowed to use this function";
			return;
		}

		$this->dispatch('status-message', 'Starting to delete the RADAR dataset');
		$radar = new DatabaseRadarDatasetBridge($this->database);

		// if in review, stop review first
		if($this->database->radar_status == 2)
		{
			// persistent publication has already been requested.
			// Therefore we must first end the review (can't delete a dataset in review)
			if(!$radar->endreview())
			{
				$this->error = $radar->message.' ('.$radar->details.')';
				return;
			}
		}

		if($radar->delete())
		{
			$this->id = null;
			$this->dispatch('radar-status-changed', 'The database has been deleted'); // let other livewire components know the radar status has changed
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
		$start = microtime(true);
		$this->reset('error');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->publish())
		{
			app('log')->info('Database persistent publication approved', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'radar_id' => $this->database->radar_id,
				'user_id' => auth()->user()->id,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start

			]);
			DatabasePersistentPublicationApproved::dispatch($this->database->id);
			$this->dispatch('radar-status-changed', 'Database published'); // let other livewire components know the radar status has changed
		}
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
		$start = microtime(true);
		$this->reset('error');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->endreview())
		{
			$this->error = 'End Review: '.$radar->message . ' ('.$radar->details .')';
			return;
		}
		else
		{
			app('log')->notice('Rejecting persistent publication', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'radar_id' => $this->database->radar_id,
				'user_id' => auth()->user()->id,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start
			]);
			DatabasePersistentPublicationRejected::dispatch($this->database->id);
		}

		$this->refreshStatus();
	}

	/*
	* Remove the DOI from the Ecosystem and all links to the Datathek. But it does nothing in the Datathek.
	*/
	public function resetDOI()
	{	
		$this->reset('error');
		$this->database->resetRADAR();
		$this->refreshStatus();
	}

	public function render()
	{
		return view('livewire.database-radar-actions');
	}

	public function validateMetadata()
	{
		$this->error = null;
		$radar = new DatabaseRadarDatasetBridge($this->database);
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
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->read())
		{
			$this->id = $radar?->radar_dataset?->id ?? null;
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
			if($this->database->radar_id)
				$this->error = $radar->message;
			$this->id = null;
			$this->state = null;
			$this->doi = null;
			$this->size = null;
			$this->radar_content = null;
			$this->canUpload = false;
			$this->setState('');
		}
		$this->radar_status = $this->database->radar_status;
		$this->publishing = app(DatabaseStopPublishingToRadar::class)->isPublishing($this->database);
		$this->emptying = app(DatabaseStopPublishingToRadar::class)->isEmptying($this->database);
		$this->updateCopyProgress();
	}

	/*
	 * Number of datasets copied to RADAR while publishing (radar_status 2). A dataset counts as
	 * copied if it has a RADAR folder and all its datafiles have been uploaded.
	 * Polled every 30 s by the view while radar_status is 2 (database queries only, no RADAR request).
	 */
	public function updateCopyProgress()
	{
		$oldStatus = $this->radar_status;
		$this->database->refresh();
		$this->radar_status = $this->database->radar_status;
		if($this->radar_status != $oldStatus && $oldStatus == 2)
		{
			// publishing has finished or was stopped: refresh everything once (calls this function again)
			$this->refreshStatus();
			return;
		}

		if($this->radar_status == 2)
		{
			$this->totalDatasets = $this->database->datasets()->count();
			$this->copiedDatasets = Dataset::where('database_id', $this->database->id)
				->whereNotNull('radar_id')
				->whereDoesntHave('datafiles', function ($query) {
					$query->whereNull('radar_id');
				})
				->count();
		}
		else
		{
			$this->copiedDatasets = null;
			$this->totalDatasets = null;
		}
	}
}


