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
use Illuminate\Support\Facades\Storage;

class Service implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Widget $widget,
        public Datafile $datafile
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        //jw:note When you modify a 'Job', you must restart the queue (./artisan queue:restart) since
        //jw:note otherwise your changes won't be used (old version cached).
        //
        app('log')->info('Service::handle() - START');
        app('log')->info('Service::handle() - $this->widget->id = ' . $this->widget->id);
        app('log')->info('Service::handle() - $this->datafile->id = ' . $this->datafile->id);
        $service = $this->widget->service;
        app('log')->info('Service::handle() - $this->service->id = ' . $service->id);
        $directory=storage_path('app/services/' . $service->id);
        app('log')->info('Service::handle() - $directory = ' . $directory);
        app('log')->info('Service::handle() - $service->parameters = ' . $service->parameters);
        $process = $service->exe . ' ' . $service->parameters . ' "' . $this->datafile->absolutepath() . '"';
        app('log')->info('Service::handle() - $process = ' . $process);
        app('log')->info('Service::handle() - run process');
        //$result = Process::quietly()->path($directory)->run($process); // run without output
        $result = Process::path($directory)->run($process); // run with output
        // write output to a service log file in the datafile directory
        $datafilelogfile = $this->datafile->directory() . '/service-' . $service->id . '.stdout';
        $datafileerrorfile = $this->datafile->directory() . '/service-' . $service->id . '.stderr';
        app('log')->info('Service::handle() - logging output to file ' . Storage::disk('sonicom-data')->path($datafilelogfile));
        app('log')->info('Service::handle() - logging errors to file ' . Storage::disk('sonicom-data')->path($datafileerrorfile));
        Storage::disk('sonicom-data')->put($datafilelogfile, $result->output());
        Storage::disk('sonicom-data')->put($datafileerrorfile, $result->errorOutput());
        if($result->successful())
            app('log')->info('Service::handle() - process finished successfully (exitCode: ' . $result->exitCode() . ')');
        if($result->failed())
            app('log')->info('Service::handle() - process failed (exitCode: ' . $result->exitCode() . ')');
        DatafileProcessed::dispatch($this->datafile->id);
        app('log')->info('Service::handle() - END');
}
}
