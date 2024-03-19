<?php

namespace App\Observers;

use App\Models\Datafile;
use App\Events\DatafileUploaded;

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
        $path = $datafile->path();
        $location = $datafile->location;
        DatafileUploaded::dispatch($datafile);
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
