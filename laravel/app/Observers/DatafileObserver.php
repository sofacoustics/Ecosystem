<?php

namespace App\Observers;

use App\Jobs\HRTFCreateFigures;
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
        $localpath = $datafile->localpath();
        //HRTFCreateFigures::dispatch($datafile);
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
            $tool = $datasetdef->tool;
            if($tool == null)
            {
                app('log')->info('DatafileObserver::updated() - no tool defined');
                return;
            }
            else
                app('log')->info('DatafileObserver::updated() - tool = ' . $tool);
            $storage_path = storage_path();
            $storage_disk_path = Storage::disk('public')->path($datafile->location);
            $path = Storage::path($datafile->location);
            app('log')->info('DatafileObserver::updated() - $tool->functionname = ' . $tool->functionname); 
            //jw:note If you want to debug a job, you *must* use the 'sync' queue, not the 'database' queue
            app('log')->info('DatafileObserver::updated() - name = ' . $datafile->name . ' location = ' . $datafile->location . ' path = ' . $datafile->path() . ' storage_path = ' . $storage_path . ' storage_disk_path = ' . $storage_disk_path);
            OctavePipe::dispatch("/tmp/sonicom-octave-pipe", "$tool->functionname $storage_disk_path");
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
