<?php

namespace App\Jobs;

use App\Models\Datafile;
use App\Models\Widget;
use App\Events\DatafileProcessed;

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
        public Widget $widget,
        public Datafile $datafile
        //jw:todo maybe pass strings rather than objects here?
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        app('log')->info('OctaveCli::handle() - START');
        app('log')->info('OctaveCli::handle() - $this->widget->id = ' . $this->widget->id);
        $widgetpath=storage_path('app/widgets/'.$this->widget->id);
        app('log')->info('OctaveCli::handle() - $widgetpath = ' . $widgetpath);
        app('log')->info('OctaveCli::handle() - $this->datafile->id = ' . $this->datafile->id);
        app('log')->info('OctaveCli::handle() - $this->datafile->directory() = ' . $this->datafile->directory());
        app('log')->info('OctaveCli::handle() - $this->datafile->absolutepath = ' . $this->datafile->absolutepath());
        $process = 'octave-cli ' . $this->widget->scriptname . " \"" . $this->datafile->absolutepath() . "\"";
        app('log')->info('OctaveCli::handle() - $process = ' . $process);
        app('log')->info('OctaveCli::handle() - run process');
        $result = Process::quietly()->path($widgetpath)->run($process);
        app('log')->info('OctaveCli::handle() - process finished');
        //app('log')->info('OctaveCli::handle() - $result->output() = ' . $result->output());
        //file_put_contents('/var/www/sonicom_dev_live/storage/logs/job-octavecli.log', $result->output());
        DatafileProcessed::dispatch($this->datafile->id);
        app('log')->info('OctaveCli::handle() - END');

    }
}
