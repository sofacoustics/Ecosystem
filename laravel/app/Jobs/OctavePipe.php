<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OctavePipe implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pipename; 
    public $parameters;
    /**
     * Create a new job instance.
     */
    public function __construct($pipename, $parameters) 
    {
        //
        $this->pipename = $pipename;
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      app('log')->info('OctavePipe::handle()');
      if(!file_exists($this->pipename))
      {
        app('log')->info('OctavePipe::handle() - pipe file ' . $this->pipename . ' doesn\'t exists!');
        $this->fail();
      }
      else
      {
        if(!file_put_contents($this->pipename, $this->parameters))
        {
          app('log')->info('OctavePipe::handle() - Failed to write to ' . $this->pipename);
        }
        else
        {
          app('log')->info('OctavePipe::handle() - Written to ' . $this->pipename);
        }
      }
    }

    public function failed()
    {
      // Called when job is failing
      app('log')->info('OctavePipe::failed()');
    }
}
