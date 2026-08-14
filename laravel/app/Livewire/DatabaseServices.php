<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use App\Models\Datafile;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DatabaseServices extends Component
{
	public $database;

	public $error; // any error message to display

	public $logs_failed; // logs with fails
	public $jobs; // jobs with datafiles for which the service failed
	public $scheduled; // collection of true|false saying whether datafiles in $jobs are scheduled to be rerun

	public function mount($database)
	{
		$this->database = $database;

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

	public function render()
	{
		return view('livewire.database-services');
	}
}


