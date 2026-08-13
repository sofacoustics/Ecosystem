<?php

namespace App\Livewire;

use Livewire\Component;

use App\Events\DatabasePersistentPublicationApproved;
use App\Events\DatabasePersistentPublicationRejected;
use App\Jobs\DatabasePublishToRadar;
use App\Services\DatabaseRadarDatasetBridge;
use Illuminate\Support\Facades\DB;
use App\Models\Datafile;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DatabaseServices extends Component
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
	public $last_retrieved = null;

	// true if we haven't uploaded yet
	public $canUpload = false;

	public $error; // any error message to display

	public $logs_failed; // logs with fails
	public $jobs; // jobs with datafiles for which the service failed
	public $scheduled; // collection of true|false saying whether datafiles in $jobs are scheduled to be rerun

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

		$this->jobs = collect();
		$jobs = DB::table('jobs')->orderby('created_at')->get();
		$datafiles = collect();
		foreach($jobs as $job)
		{
			$pos = strpos($job->payload, "Datafile");
			if($pos !== false)
			{
				$str = substr($job->payload, $pos+15+9);
				$datafile_id = Str::match('/^(\d+)/', $str);
				$job->datafile = Datafile::where('id', $datafile_id)->first();
			}
			else
				$job->datafile = null;
			$this->jobs->push($job);
			$datafiles->push($job->datafile);
		}

		$this->logs_failed = collect();
		$this->scheduled = collect();
		$logs = DB::table('service_logs')->orderby('datafile_id')->get();
		$logs = $logs->unique('datafile_id');		
		foreach($logs as $log_df)
		{
			$logs_unique = DB::table('service_logs')->where('datafile_id', $log_df->datafile_id)->orderby('created_at','desc')->get();
			if($logs_unique[0]->exit_code != 0)
			{ $log = $logs_unique[0];
				$log->datafile = Datafile::where('id', $log->datafile_id)->first();
				$this->logs_failed->push($log);
				$this->scheduled->push($datafiles->contains('id', $log->datafile->id));
			}
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

	public function render()
	{
		return view('livewire.database-services');
	}
}


