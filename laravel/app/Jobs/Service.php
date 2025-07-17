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



	private ServiceModel $service;

	/**
	 * Create a new job instance.
	 */
	public function __construct(
		public Widget $widget,
		public Datafile $datafile
	) {
		$this->service = $this->widget->service;
		$this->timeout = $this->service->timeout + 10; // This 'timeout' appears to be independent of the Process timeout and needs to be high enough
		$this->queue = 'services';
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		////////////////////////////////////////////////////////////////////////////////
		//
		//  Note:
		//
		//  When you modify a 'Job', you must restart the queue
		//
		//      ./artisan queue:restart
		//
		//  since otherwise your changes won't be used (old version cached).
		//
		////////////////////////////////////////////////////////////////////////////////
		$widget_id=$this->widget->id;
		$service_id = $this->service->id;
		$datafile_id = $this->datafile->id;
		$directory=storage_path('app/services/' . $this->service->id);
		$log = "  widget=$widget_id, service=$service_id, datafile=$datafile_id, directory=$directory\n";
		#$command = 'bash -c "setsid bash -c \'' .  $this->service->exe . ' ' . $this->service->parameters . ' \"' . $this->datafile->absolutepath() . '\"' . '\'"';
		#$command = 'bash -c "setsid bash -c \'' .  $this->service->exe . ' ' . $this->service->parameters . ' \"' . $this->datafile->absolutepath() . '\"' . '\'"';
		//$command = 'bash -c "setsid bash -c \'sleep 20 && echo \"Child finished.\" >> /tmp/child_output.txt\'"';
		$command = $this->service->exe . ' ' . $this->service->parameters . ' "' . $this->datafile->absolutepath() . '"';
		$log .= ' command='.$command."\n";
		//$command = 'exec setsid ' . $this->service->exe . ' ' . $this->service->parameters . ' "' . $this->datafile->absolutepath() . '"';

		$start = microtime(true);

		/*
		$process = Process::timeout($timeout)
			->path($directory)
			//->setPty(true) // enable process group termination
			->start($command); // run with output
		 */

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
		//$args = array_merge($args, explode(' ', $this->service->parameters));
		$args[] = $this->datafile->absolutepath();
		foreach($envvars as $k => $v)
			$log .= "  environmental variable: $k => $v\n";
		foreach($args as $arg)
			$log .= "  command argument: $arg\n";
		//Log::info('The command arguments', $args);


		//$process = new Process([$this->service->exe, $this->service->parameters, $this->datafile->absolutepath()], null,[
		$process = new Process($args, null,$envvars);
			/*
		$process = new Process(['xvfb-run', '-a', 'octave-cli', 'BRIRGeometry.m', $this->datafile->absolutepath()], null,[
			'XDG_CACHE_HOME' => '/run/user/33/sonicom-xdg-cache-home',
			'XDG_RUNTIME_DIR' => '/run/user/33',
		]);*/
		$process->setWorkingDirectory($directory);
		$process->setTimeout($this->service->timeout); // use class timeout (set from service column)

		$process->start();

		$pid = $process->getPid();

        $jobid = $this->job?->getJobId();

		app('log')->info("Job $jobid Process $pid has started:", ['data' => $args]);
		try {
			// Periodically check for timeout while the process is running
			while ($process->isRunning()) {
				// Find child PIDs
				$findChildren = new Process(['pgrep', '-P', $pid]);
				$findChildren->run();
				$childPids = array_filter(explode("\n", $findChildren->getOutput()));
				$process->checkTimeout(); // This will throw if timeout is reached
				usleep(100000); // Sleep for 0.1 seconds
			}
			$output = $process->getOutput();
			$errorOutput = $process->getErrorOutput();
			app('log')->info("Process $pid has finished");
		} catch (ProcessTimedOutException $e) {
			app('log')->info("Process $pid has reached it's timeout");
			// Kill child processes
			foreach ($childPids as $childPid) {
				if (is_numeric($childPid)) {
					$log .= "  Killing $childPid\n";
					posix_kill((int)$childPid, SIGKILL);
				}
			}

			//$process->signal(SIGKILL);
			//$log .= " Killing via SIGKILL\n";
		}


		$duration = microtime(true) - $start;
		$execution = "execution time: $duration";
		$exitCode = $process->getExitCode();
		$exitCodeText = $process->getExitCodeText();

		$datafilelogfile = $this->datafile->directory() . '/service-' . $this->service->id . '-PID-' . $pid . '.stdout';
		$datafileerrorfile = $this->datafile->directory() . '/service-' . $this->service->id . '-PID-' . $pid . '.stderr';
		$log .= "  logging output to file " . Storage::disk('sonicom-data')->path($datafilelogfile) . "\n";
		$log .= "  logging errors to file " . Storage::disk('sonicom-data')->path($datafileerrorfile) . "\n";

/*
		// wait for completion with timeout handling
		$successful = false;
		try {
			$result = $process->wait();
			$exitCode = $result->exitCode();
			$successful = $result->successful();
			$output = $result->output();
			$errorOutput = $result->errorOutput();
			// write output to a service log file in the datafile directory
		} catch (ProcessTimedOutException $e) {
			// Handle timeout
            //$process->signal(SIGKILL); // Force kill if needed
            $process->stop();
			$exitCode = -99; // timeout
			$output = "";
 			$errorOutput = "$timeout second timeout reached";
			$log .= "  exception: $timeout second timeout reached!\n";
        }
*/
		$duration = microtime(true) - $start;
		$this->datafile->last_service_error_code = $exitCode;
		$this->datafile->save();
		// add to service log table
		$serviceLog = new ServiceLog();
		$serviceLog->service_id = $this->service->id;
		$serviceLog->datafile_id  = $this->datafile->id;
		$serviceLog->exit_code  = $exitCode;
		$serviceLog->exit_code_text = $exitCodeText;
		$serviceLog->execution_time  = $duration;
		$serviceLog->name  = $this->service->name;
		$serviceLog->description  = $this->service->description;
		$serviceLog->exe  = $this->service->exe;
		$serviceLog->parameters  = $this->service->parameters;
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
		app('log')->info("Process $pid info:\n$log"); // log all in one go so it doesn't get interspersed with other log messages
	}
}
