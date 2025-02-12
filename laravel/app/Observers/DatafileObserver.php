<?php

namespace App\Observers;

use App\Jobs\HRTFCreateFigures;
use App\Jobs\OctaveCli;
use App\Jobs\OctavePipe;
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
        app('log')->info("DatafileObserver::created - $datafile->location ");
        //dd($datafile);
        $path = $datafile->path();
        app('log')->info("DatafileObserver::created - $path");
        $widget = $datafile->datasetdef->widget;
        if($widget)
        {
            //dd($datafile->datasetdef->widget);
            if($widget->name == "Octave HRTF")
                OctaveCli::dispatch($widget, $datafile);
        }
    }

    /**
     * Handle the Datafile "updated" event.
     */
    public function updated(Datafile $datafile): void
    {
        //
        app('log')->info('DatafileObserver::updated()');
        $path = $datafile->path();
        $name = $datafile->name;
        $location = $datafile->location;
        // if location exists, then send to Octave job
        if($datafile->location != null)
        {
            //jw:tmp:start
            $dataset = $datafile->dataset->name;
            app('log')->info('DatafileObserver::updated() - dataset->name = ' . $dataset);
            $database = $datafile->dataset->database->name;
            app('log')->info('DatafileObserver::updated() - database->name = ' . $database);
            $datasetdef = $datafile->datasetdef;
            app('log')->info('DatafileObserver::updated() - datasetdef = ' . $datasetdef);
            $widget = $datasetdef->widget;
            if($widget == null)
            {
                app('log')->info('DatafileObserver::updated() - no widget defined');
                return;
            }
            else
                app('log')->info('DatafileObserver::updated() - widget = ' . $widget);
            $storage_disk_path = Storage::disk('sonicom-data')->path($datafile->location);
            app('log')->info('DatafileObserver::updated() - $widget->functionname = ' . $widget->functionname);
            //jw:note If you want to debug a job, you *must* use the 'sync' queue, not the 'database' queue
            app('log')->info('DatafileObserver::updated() - name = ' . $datafile->name . ' location = ' . $datafile->location . ' path = ' . $datafile->path() . ' storage_disk_path = ' . $storage_disk_path . ' storage_disk_path = ' . $storage_disk_path);
            app('log')->info('DatafileObserver::updated() - $datafile->path() = ' . $datafile->path());
            app('log')->info('DatafileObserver::updated() - $datafile->absolutepath() = ' . $datafile->absolutepath());

            OctaveCli::dispatch($widget, $datafile);
            //HRTFCreateFigures::dispatch($widget->scriptname, $storage_disk_path);
            //OctavePipe::dispatch("/tmp/sonicom-octave-pipe", "$widget->functionname $storage_disk_path");
        }
        //DatafileUploaded::dispatch($datafile);
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
}
