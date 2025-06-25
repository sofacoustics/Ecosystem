<?php

namespace App\Jobs;

use App\Models\Datafile;
use App\Models\Service as ServiceModel;
use App\Models\Widget;
use App\Events\DatafileProcessed;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

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
        $log = "Service::handle()\n";
        $widget_id=$this->widget->id;
        $service_id = $this->service->id;
        $datafile_id = $this->datafile->id;
        $directory=storage_path('app/services/' . $this->service->id);
        $log .= "  widget=$widget_id, service=$service_id, datafile=$datafile_id, directory=$directory\n";
        $processstring = $this->service->exe . ' ' . $this->service->parameters . ' "' . $this->datafile->absolutepath() . '"';
        $process = Process::path($directory)->start($processstring); // run with output
        $pid = $process->id();
        $log .= "  process=$processstring (PID: $pid)\n";
        $result = $process->wait();
        // write output to a service log file in the datafile directory
        $datafilelogfile = $this->datafile->directory() . '/service-' . $this->service->id . '-PID-' . $pid . '.stdout';
        $datafileerrorfile = $this->datafile->directory() . '/service-' . $this->service->id . '-PID-' . $pid . '.stderr';
        $log .= "  logging output to file " . Storage::disk('sonicom-data')->path($datafilelogfile) . "\n";
        $log .= "  logging errors to file " . Storage::disk('sonicom-data')->path($datafileerrorfile) . "\n";
        Storage::disk('sonicom-data')->put($datafilelogfile, $result->output());
        Storage::disk('sonicom-data')->put($datafileerrorfile, $result->errorOutput());
        if($result->successful())
            $log .= "  process finished successfully (exitCode: " . $result->exitCode() . ")\n";
        if($result->failed())
            $log .= "  process failed (exitCode: " . $result->exitCode() . ")\n";
        DatafileProcessed::dispatch($this->datafile->id);
        app('log')->info($log); // log all in one go so it doesn't get interspersed with other log messages
    }
}
