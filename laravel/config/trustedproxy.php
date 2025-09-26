<?php
/*
 * Use a .env value for trusted proxies rather than hard-coding it
 * into the app/Http/Middleware/TrustProxies.php file.
 *
 */
return [
	    'proxies' => explode(',', env('TRUSTED_PROXIES', '')),
];

