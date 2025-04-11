<?php

namespace App\Listeners;

use App\Events\DatafileUploaded;
use App\Jobs\HRTFCreateFigures;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDatafileNotification implements ShouldQueue
{
    use InteractsWithQueue;

  	public $tries = 5;
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
      //jw:note You can only really debug a listener if the QUEUE_CONNECTION is 'sync'
      $name = $event->datafile->name;
      app('log')->info('SendDatafileNotification Listener');
      HRTFCreateFigures::dispatch($event->datafile);
      //HRTFCreateFigures::dispatchSync($event->datafile); //jw:note this didn't help. Only QUEUE_CONNECTION helped.
    }
}
