<?php

namespace App\Jobs;

use App\Models\Datafile;
use App\Models\Service as ServiceModel;
use App\Models\ServiceLog;
use App\Models\Widget;
use App\Events\DatafileProcessed;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
#use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
#use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;

/*
 *	The services are started as a 'job'.
 *	If you modify this script, you *must* restart the queue worker, otherwise
 *	your changes won't take effect.
 */
class Service implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public $timeout = 240; // default Job timeout

	private ServiceModel $service;

	/**
	 * Create a new job instance.
	 */
	public function __construct(
		public Widget $widget,
		public Datafile $datafile
	) {
		$this->service = $this->widget->service;
		// if you set this here, then the database value will be fixed at the time that
		// the job is put in the queue. Therefore, set it in handle(), which will pull
		// it out of the database when the job actually runs.
		//$this->timeout = $this->service->timeout + 10;
		$this->queue = 'services';
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		////////////////////////////////////////////////////////////////////////////////
		//
		//	Note:
		//
		//	When you modify a 'Job', you must restart the queue
		//
		//		./artisan queue:restart
		//
		//	since otherwise your changes won't be used (old version cached).
		//	This is valid when using supervisorctl too!
		//
		////////////////////////////////////////////////////////////////////////////////
		// This 'timeout' appears to be independent of the Process timeout and needs to be high enough
		// Setting $this->timeout in __construct happens when the job is queued.
		// Setting it here will pull the value from the database when the job actually runs.
		$this->timeout = $this->service->timeout + 10;
		$widget_id=$this->widget->id;
		$service_id = $this->service->id;
		$datafile_id = $this->datafile->id;
		$directory=storage_path('app/services/' . $this->service->id);
		$log = "  widget=$widget_id, service=$service_id, datafile=$datafile_id, directory=$directory\n";
		$command = $this->service->exe . ' ' . $this->service->parameters . ' "' . $this->datafile->absolutepath() . '"';
		$log .= ' command='.$command."\n";

		$start = microtime(true);

		// parse environmental variables and command arguments
		$pairs = explode(' ', $this->service->exe);
		$envvars = [];
		foreach ($pairs as $pair) {
			if (strpos($pair, '=') !== false) {
				list($key, $value) = explode('=', $pair, 2);
				$envvars[$key] = $value;
			}
			else
				$args[] = $pair;
		}
		$parameters = explode(' ', $this->service->parameters);
		foreach($parameters as $param)
			$args[] = $param;
		$args[] = $this->datafile->absolutepath();
		foreach($envvars as $k => $v)
			$log .= "  environmental variable: $k => $v\n";
		foreach($args as $arg)
			$log .= "  command argument: $arg\n";

		$process = new Process($args, null,$envvars);
		$process->setWorkingDirectory($directory);
		$process->setTimeout($this->service->timeout); // use class timeout (set from service column)

		$process->start();

		$pid = $process->getPid();

		$jobid = $this->job?->getJobId();

		// add to service log table
		$serviceLog = new ServiceLog();
		$serviceLog->service_id = $this->service->id;
		$serviceLog->datafile_id  = $this->datafile->id;
		$serviceLog->exit_code	= -666;
		$serviceLog->exit_code_text = "Job has been started. If this text remains here, then the process was killed by supervisorctl or itself and Houston - we have a problem!";
		$serviceLog->execution_time  = microtime(true) - $start;
		$serviceLog->name  = $this->service->name;
		$serviceLog->description  = $this->service->description;
		$serviceLog->exe  = $this->service->exe;
		$serviceLog->parameters  = $this->service->parameters;
		$serviceLog->save();

		app('log')->channel('services_stack')->notice("Job $jobid Process $pid has started (timeout: $this->timeout):", ['data' => $args]);
		app('log')->channel('services_stack')->debug("Job $jobid Process $pid has started (timeout: $this->timeout): $log");
		try {
			// Periodically check for timeout while the process is running
			while ($process->isRunning()) {
				// Find child PIDs
				$findChildren = new Process(['pgrep', '-P', $pid]);
				$findChildren->run();
				$childPids = array_filter(explode("\n", $findChildren->getOutput()));
				app('log')->channel('services_stack')->debug("Job $jobid Process $pid is running");
				$serviceLog->execution_time  = microtime(true) - $start;
				$serviceLog->stdout = $process->getOutput();
				$serviceLog->stderr = $process->getErrorOutput();
				$serviceLog->save();
				$process->checkTimeout(); // This will throw if timeout is reached
				usleep(100000); // Sleep for 0.1 seconds
			}
			$output = $process->getOutput();
			$errorOutput = $process->getErrorOutput();
			app('log')->channel('services_stack')->debug("Process $pid has finished");
		} catch (ProcessTimedOutException $e) {
		    app('log')->channel('services_stack')->debug("Job $jobid Process $pid has reached it's timeout");
			app('log')->channel('services_stack')->warning("Process $pid has reached it's timeout");
		    app('log')->channel('services_stack')->debug("Job $jobid Process $pid: sending children SIGKILL");
			// Kill child processes
			foreach ($childPids as $childPid) {
				if (is_numeric($childPid)) {
					$log .= "  Killing $childPid\n";
					posix_kill((int)$childPid, SIGKILL);
				}
			}
		} catch (\Exception $e) {
			app('log')->channel('services_stack')->warning("Process $pid - generic error");
		} finally {
			app('log')->channel('services_stack')->debug("Process $pid - entering 'finally' section");
			$duration = microtime(true) - $start;
			$execution = "execution time: $duration";
			$exitCode = $process->getExitCode();
			$exitCodeText = $process->getExitCodeText();

			$datafilelogfile = $this->datafile->directory() . '/service-' . $this->service->id . '-PID-' . $pid . '.stdout';
			$datafileerrorfile = $this->datafile->directory() . '/service-' . $this->service->id . '-PID-' . $pid . '.stderr';
			$log .= "  logging output to file " . Storage::disk('sonicom-data')->path($datafilelogfile) . "\n";
			$log .= "  logging errors to file " . Storage::disk('sonicom-data')->path($datafileerrorfile) . "\n";

			$duration = microtime(true) - $start;
			$this->datafile->last_service_error_code = $exitCode;
			$this->datafile->save();
			// add to service log table
			$serviceLog->exit_code	= $exitCode;
			$serviceLog->exit_code_text = $exitCodeText;
			$serviceLog->execution_time  = $duration;
			if(isset($output))
			{
				$serviceLog->stdout  = $output;
				Storage::disk('sonicom-data')->put($datafilelogfile, $output);
			}
			if(isset($errorOutput))
			{
				$serviceLog->stderr  = $errorOutput;
				Storage::disk('sonicom-data')->put($datafileerrorfile, $errorOutput);
			}
			$serviceLog->save();
			$log .= "  service timeout value: " . $this->timeout . " process timeout value: " . $this->service->timeout . "\n";
			if($process->isSuccessful())
				$log .= "  process finished successfully after $duration seconds (exitCode: " . $exitCode . ")\n";
			else
				$log .= "  process failed after $duration seconds (exitCode: " . $exitCode . ")\n";
			DatafileProcessed::dispatch($this->datafile->id);
			if($exitCode!=0)
				app('log')->channel('services_stack')->warning("Process $pid info:\n$log"); // log all in one go so it doesn't get interspersed with other log messages
			else
				app('log')->channel('services_stack')->notice("Process $pid info:\n$log"); // log all in one go so it doesn't get interspersed with other log messages
		}
	}
}
