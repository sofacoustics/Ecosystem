<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
		{
			parent::boot();

			// log sending emails here, so we don't have to do it anywhere else
			\Event::listen(MessageSending::class, function (MessageSending $event) {
				// You can inspect $event->message, $event->to, etc.
				$addresses = array_map(function ($addr) {
					// Address has getAddress() and getName()
					return $addr->getAddress();
				}, $event->message->getTo());
				Log::channel('mail')->debug('Sending...', [
					'subject' => $event->message->getSubject(),
					'addresses' => $addresses
				]);
			});

			// log sent emails to 'mail' log
			\Event::listen(MessageSent::class, function (MessageSent $event) {
				$subject = $event->message->getSubject();
				$addresses = array_map(function ($addr) {
					// Address has getAddress() and getName()
					return $addr->getAddress();
				}, $event->message->getTo());
				$messageId = $event->sent->getMessageId();
				Log::channel('mail')->info('Email sent', [
					'subject' => $subject,
					'addresses' => $addresses,
					'message_id' => $messageId
				]);
			});
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
