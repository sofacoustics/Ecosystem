<?php

namespace App\Jobs;

use App\Models\Datafile;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;

class HRTFCreateFigures implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
		public function __construct(
			public Datafile $datafile
		) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
			//
			$bla = 2; // do something here
      $result = Process::run('octave-cli /home/jw/git/isf-sonicom-laravel/octave/octavetest.m');
      // write to octave pipe file here
      file_put_contents('/tmp/octave-pipe "text from laravel handle!"');
			$bla = 3;
			sleep(2);
    }
}
