<?php

namespace App\Listeners;

use App\Events\DatafileUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDatafileNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DatafileUploaded $event): void
    {
        //
        $path = $event->datafile->path();
    }
}
