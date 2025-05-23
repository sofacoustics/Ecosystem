<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

		'orcid' => [
			'api_url' => env('ORCID_API_URL', 'https://orcid.org/'), // Adjust for sandbox: 'https://sandbox.orcid.org/v3.0'
			'client_id' => env('ORCID_CLIENT_ID'),
			'client_secret' => env('ORCID_CLIENT_SECRET'),
			// 'redirect' => env('ORCID_REDIRECT_URI', '/orcid/callback'), // You'll define the route
],

];
