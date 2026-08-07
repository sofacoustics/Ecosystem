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
				//jw:note We now have an explicit function to start the service. This gives us more
				//        control about when it is called
    }

    /**
     * Handle the Datafile "updated" event.
     */
    public function updated(Datafile $datafile): void
    {
        app('log')->debug('DatafileObserver::updated()');
				//jw:note we're no longer running a service when datafile attributes change, since they may be
				//        attributes that require no re-rendering. Running the service can, however, be called
				//        explicity via the Datafile::dispatchService() function. 
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

}
