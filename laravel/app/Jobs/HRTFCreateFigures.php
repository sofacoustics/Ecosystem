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

    /*
    public $pipefile;
    public $function;
    public $parameters;
    */

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
      app('log')->info('HRTFCreateFigures::handle()');
      //app('log')->info('HRTFCreateFigures::handle() - $datafile->name = ' + $datafile->name);
      app('log')->info('HRTFCreateFigures::handle() - $datafile->name = ' . $this->datafile->name);
			//
      //$result = Process::run('octave-cli /home/jw/git/isf-sonicom-laravel/octave/octavetest.m');
      $name = $this->datafile->name; 
      $scriptname = $this->datafile->widgets->scriptname;
      app('log')->info('HRTFCreateFigures::handle() - $datafile->widgets-scriptname = ' . $scriptname);
    }
}
