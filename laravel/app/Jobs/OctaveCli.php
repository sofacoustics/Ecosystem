<?php

namespace App\Jobs;

use App\Models\Datafile;
use App\Models\Tool;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;

class OctaveCli implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Tool $tool,
        public Datafile $datafile
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        app('log')->info('OctaveCli::handle()');
        app('log')->info('OctaveCli::handle() - $this->tool->id = ' . $this->tool->id);
        $toolpath=storage_path('app/tools/'.$this->tool->id);
        app('log')->info('OctaveCli::handle() - $toolpath = ' . $toolpath);
        app('log')->info('OctaveCli::handle() - $this->datafile->id = ' . $this->datafile->id);
        app('log')->info('OctaveCli::handle() - $this->datafile->absolutepath = ' . $this->datafile->absolutepath());
        $process = 'octave-cli ' . $this->tool->scriptname . " " . $this->datafile->absolutepath();
        app('log')->info('OctaveCli::handle() - $process = ' . $process);
        $result = Process::path($toolpath)->run($process);
    }
}
