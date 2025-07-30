<?php

namespace App\Listeners;

use App\Events\DatabasePersistentPublicationApproved;
use App\Events\DatabasePersistentPublicationRejected;
use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAdminEmail implements ShouldQueue
{
    use InteractsWithQueue;

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
	public function handle(
		DatabasePersistentPublicationApproved
		|DatabasePersistentPublicationRejected
		|DatabasePublishedAwaitingApproval
	    |DatabasePublishingFailed $event
	): void
	{
		//jw:note This listener was created with following command
		//
		//  ./artisan make:listener --event DatabasePublishedAwaitingApproval --queued
		//
		//  When playing around with these, you may need to clear caches and composer
		//
		//  e.g. ./artisan event:clear && ./artisan event:cache && composer dump-autoload 
		//
		//  and possible restart queues
		//

		$eventClass = get_class($event);
		//
		app('log')->debug('SendAdminEmail::handle() for event ' . $eventClass);
		//jw:todo we could use this for a more generic admin email setup...
    }
}
