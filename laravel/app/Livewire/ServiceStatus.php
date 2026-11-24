<?php

namespace App\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\DB;
use App\Models\Database;
use App\Models\Datafile;

class ServiceStatus extends Component
{
	public $error; // any error message to display

	public $logs_failed; // logs with fails
	public $jobs; // array of Service jobs (one row per job, plain data only)
	public $jobGroups; // array of all other jobs (e.g. RADAR uploads), grouped by job class, queue and database
	public $scheduled; // collection of true|false saying whether datafiles in $logs_failed are scheduled to be rerun

	public function mount()
	{
		// Note: public properties are sent to the browser (Livewire snapshot), so keep only the
		// displayed fields here and never the job payload (thousands of upload jobs would bloat the page).
		$jobs = [];
		$groups = [];
		$datafileIds = [];
		$databaseIds = [];

		$rows = DB::table('jobs')->select('id', 'queue', 'payload', 'attempts', 'created_at')->orderBy('created_at')->cursor();
		foreach($rows as $row)
		{
			$payload = json_decode($row->payload, true);
			$class = $payload['displayName'] ?? 'unknown';
			$command = $payload['data']['command'] ?? '';
			$type = class_basename($class);
			// jobs.created_at is a Unix timestamp; rows are sorted by it, so first/last per group stay correct
			$created_at = date('Y-m-d H:i:s', (int)$row->created_at); // app timezone (config app.timezone)

			if($type === 'Service')
			{
				// serialized model: ..."App\Models\Datafile";s:2:"id";i:123;...
				$datafile_id = preg_match('/"App\\\\Models\\\\Datafile";s:2:"id";i:(\d+);/', $command, $m) ? (int)$m[1] : null;
				if($datafile_id) $datafileIds[] = $datafile_id;
				$jobs[] = [
					'id' => $row->id,
					'queue' => $row->queue,
					'attempts' => $row->attempts,
					'created_at' => $created_at,
					'datafile_id' => $datafile_id,
				];
			}
			else
			{
				$database_id = preg_match('/"App\\\\Models\\\\Database";s:2:"id";i:(\d+);/', $command, $m) ? (int)$m[1] : null;
				if($database_id) $databaseIds[] = $database_id;
				$key = $type.'|'.$row->queue.'|'.$database_id;
				if(!isset($groups[$key]))
					$groups[$key] = [
						'type' => $type,
						'queue' => $row->queue,
						'database_id' => $database_id,
						'count' => 0,
						'attempted' => 0, // jobs with attempts > 0 (running or retried)
						'first_created_at' => $created_at,
						'last_created_at' => $created_at,
					];
				$groups[$key]['count']++;
				if($row->attempts > 0) $groups[$key]['attempted']++;
				$groups[$key]['last_created_at'] = $created_at;
			}
		}

		// load names with one query each instead of one query per job
		$datafileNames = Datafile::whereIn('id', array_unique($datafileIds))->pluck('name', 'id');
		foreach($jobs as &$job)
			$job['datafile_name'] = $job['datafile_id'] ? ($datafileNames[$job['datafile_id']] ?? null) : null;
		unset($job);
		$databaseTitles = Database::whereIn('id', array_unique($databaseIds))->pluck('title', 'id');
		foreach($groups as &$group)
			$group['database_title'] = $group['database_id'] ? ($databaseTitles[$group['database_id']] ?? null) : null;
		unset($group);

		$this->jobs = $jobs;
		$this->jobGroups = array_values($groups);

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
				$this->scheduled->push(in_array($log->datafile_id, $datafileIds));
			}
		}
	}

	public function render()
	{
		return view('livewire.service-status');
	}
}
