<?php

namespace App\Observers;

use App\Events\DatafileUploaded;
use App\Jobs\Service;
use App\Models\Datafile;
use App\Services\DatafileRadarFileBridge;

use Illuminate\Support\Facades\Storage;

class DatafileObserver
{
    /**
     * Handle the Datafile "created" event.
     */
    public function created(Datafile $datafile): void
    {
        //
        app('log')->debug("DatafileObserver::created");
        $this->dispatchService($datafile);
    }

    /**
     * Handle the Datafile "updated" event.
     */
    public function updated(Datafile $datafile): void
    {
        app('log')->debug('DatafileObserver::updated()');
        $this->dispatchService($datafile);
    }

    /**
     * Handle the Datafile "deleting" event.
     */
    public function deleting(Datafile $datafile): void
	{
		// delete physical file directory
		$directory = $datafile->directory();
		Storage::disk('sonicom-data')->deleteDirectory($directory);
		// delete from RADAR
		if($datafile->radar_id)
		{
			app('log')->info('DatafileObserver::deleted() - deleting RADAR file');
			$radar = new DatafileRadarFileBridge($datafile);
			if(!$radar->delete())
			{
				app('log')->warning('DatafileObserver::deleted() - failed to delete RADAR file');
			}
			//jw:note Can't use job here, since datafile won't exist when job starts
		}
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

	////////////////////////////////////////////////////////////////////////////////
	// private
	////////////////////////////////////////////////////////////////////////////////

    private function dispatchService(Datafile $datafile)
    {
        $widget = $datafile->datasetdef->widget;
        if($widget)
        {
            app('log')->debug("dispatchService (widget: $widget->id, datafile: $datafile->id)");
            $service = $widget->service;
            if($service)
            {
				//jw:note If you want to debug a job using vscode, you *must* use the 'sync' queue, not the 'database' queue
                Service::dispatch($widget, $datafile);
            }
        }
		else
		{
			app('log')->warning("dispatchService - NO WIDGET defined", [
				'feature' => 'widgets',
				'datafile_id' => $datafile->id,
				'datasetdef_id' => $datafile->datasetdef->id
			]);
		}
    }
}
