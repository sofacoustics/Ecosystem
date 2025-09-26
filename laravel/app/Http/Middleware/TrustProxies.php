<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
	/**
	* The trusted proxies for this application.
	*
	* @var array<int, string>|string|null
	*
	* jw:note 
	*
	*	this is necessary for reverse proxy to work, otherwise urls are not rewritten!
	*         https://www.iankumu.com/blog/laravel-nginx-reverse-proxy/
	*
	*	moved values to trustedproxy config/.env (see __construct)
	*
	*/
	protected $proxies; 

	/**
	* The headers that should be used to detect proxies.
	*
	* @var int
	*/
	protected $headers =
		Request::HEADER_X_FORWARDED_FOR |
		Request::HEADER_X_FORWARDED_HOST |
		Request::HEADER_X_FORWARDED_PORT |
		Request::HEADER_X_FORWARDED_PROTO;

	public function __construct()
	{
		// get value from config file, which gets it from .env file.
		$this->proxies = config('trustedproxy.proxies');
	}
}
