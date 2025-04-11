<?php

namespace App\Observers;

use App\Jobs\HRTFCreateFigures;
use App\Jobs\OctaveCli;
use App\Jobs\OctavePipe;
use App\Jobs\Service;
use App\Models\Datafile;
use App\Events\DatafileUploaded;
use Illuminate\Support\Facades\Storage;

class DatafileObserver
{
    /**
     * Handle the Datafile "created" event.
     */
    public function created(Datafile $datafile): void
    {
        //
        app('log')->info("DatafileObserver::created");
        $this->dispatchService($datafile);
    }

    /**
     * Handle the Datafile "updated" event.
     */
    public function updated(Datafile $datafile): void
    {
        app('log')->info('DatafileObserver::updated()');
        $this->dispatchService($datafile);
    }

    /**
     * Handle the Datafile "deleted" event.
     */
    public function deleted(Datafile $datafile): void
    {
        //
    }

    /**
     * Handle the Datafile "restored" event.
     */
    public function restored(Datafile $datafile): void
    {
        //
    }

    /**
     * Handle the Datafile "force deleted" event.
     */
    public function forceDeleted(Datafile $datafile): void
    {
        //
    }

    private function dispatchService(Datafile $datafile)
    {
        $widget = $datafile->datasetdef->widget;
        if($widget)
        {
            app('log')->info("dispatchService (widget: $widget->id, datafile: $datafile->id)");
            $service = $widget->service;
            if($service)
            {
                //jw:note If you want to debug a job, you *must* use the 'sync' queue, not the 'database' queue
                Service::dispatch($widget, $datafile);
            }
        }
        else
            app('log')->info("dispatchService - NO WIDGET defined");
    }
}
