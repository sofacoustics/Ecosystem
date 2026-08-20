<?php

// app/Mail/Mailable.php
namespace App\Mail;

use App\Models\User;

use Illuminate\Support\Facades\View;
use Illuminate\Mail\Mailable as LaravelMailable;

/*
 *	This class can initialised the '$actor' property with the name of the logged on user at the time that 
 *	the email was queued. Use 'App\Mail\Mailable' instead of 'Illuminate\Mail\Mailable'
 */
abstract class Mailable extends LaravelMailable
{
	public User|string|null $actor = null;

    public function __construct(User|string|null $actor = null)
		{
			// user name or default to 'System'
			$this->actor = $actor ?? auth()->user() ?? 'System';
		} 
}
