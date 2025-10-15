<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

	/*
	|--------------------------------------------------------------------------
	| Default Log Channel
	|--------------------------------------------------------------------------
	|
	| This option defines the default log channel that gets used when writing
	| messages to the logs. The name specified in this option should match
	| one of the channels defined in the "channels" configuration array.
	|
	*/

	'default' => env('LOG_CHANNEL', 'filtered_stack'),

	/*
	|--------------------------------------------------------------------------
	| Deprecations Log Channel
	|--------------------------------------------------------------------------
	|
	| This option controls the log channel that should be used to log warnings
	| regarding deprecated PHP and library features. This allows you to get
	| your application ready for upcoming major versions of dependencies.
	|
	*/

	'deprecations' => [
		'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
		'trace' => false,
	],

	/*
	|--------------------------------------------------------------------------
	| Log Channels
	|--------------------------------------------------------------------------
	|
	| Here you may configure the log channels for your application. Out of
	| the box, Laravel uses the Monolog PHP logging library. This gives
	| you a variety of powerful log handlers / formatters to utilize.
	|
	| Available Drivers: "single", "daily", "slack", "syslog",
	|					 "errorlog", "monolog",
	|					 "custom", "stack"
	|
	*/

	'channels' => [
		// log to services_file and all other files as well. Use this in the Services provider
		'services_stack' => [
			'driver' => 'stack',
			'channels' => ['filtered_stack', 'services_file'],
			'ignore_exceptions' => false,
		],

		'filtered_stack' => [
			'driver' => 'stack',
			// All logs sent to the stack will be passed to these two channels:
			'channels' => ['error_file', 'warning_file', 'notice_file', 'info_file', 'debug_file'],
			'ignore_exceptions' => false,
		],

		'error_file' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel-error.log'),
			'level' => 'error', // emergency, alert, critical, error
		],

		'warning_file' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel-warning.log'),
			'level' => 'warning', // emergency, alert, critical, error
		],

		'notice_file' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel-notice.log'),
			'level' => 'notice', // emergency, alert, critical, error, warning, notice
		],

		'info_file' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel-info.log'),
			'level' => 'info', // emergency, alert, critical, error, warning, notice, info
		],

		'debug_file' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel-debug.log'),
			'level' => 'debug', // Crucial: Writes everything from DEBUG up
		],

		'services_file' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel-services.log'),
			'level' => 'debug', // specify 'services_stack'
		],

		'stack' => [
			'driver' => 'stack',
			'channels' => ['single'],
			'ignore_exceptions' => false,
		],

		'single' => [
			'driver' => 'single',
			'path' => storage_path('logs/laravel.log'),
			'level' => env('LOG_LEVEL', 'debug'),
			'replace_placeholders' => true,
		],

		'daily' => [
			'driver' => 'daily',
			'path' => storage_path('logs/laravel.log'),
			'level' => env('LOG_LEVEL', 'debug'),
			'days' => 14,
			'replace_placeholders' => true,
		],

		'slack' => [
			'driver' => 'slack',
			'url' => env('LOG_SLACK_WEBHOOK_URL'),
			'username' => 'Laravel Log',
			'emoji' => ':boom:',
			'level' => env('LOG_LEVEL', 'critical'),
			'replace_placeholders' => true,
		],

		'papertrail' => [
			'driver' => 'monolog',
			'level' => env('LOG_LEVEL', 'debug'),
			'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
			'handler_with' => [
				'host' => env('PAPERTRAIL_URL'),
				'port' => env('PAPERTRAIL_PORT'),
				'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
			],
			'processors' => [PsrLogMessageProcessor::class],
		],

		'stderr' => [
			'driver' => 'monolog',
			'level' => env('LOG_LEVEL', 'debug'),
			'handler' => StreamHandler::class,
			'formatter' => env('LOG_STDERR_FORMATTER'),
			'with' => [
				'stream' => 'php://stderr',
			],
			'processors' => [PsrLogMessageProcessor::class],
		],

		'syslog' => [
			'driver' => 'syslog',
			'level' => env('LOG_LEVEL', 'debug'),
			'facility' => LOG_USER,
			'replace_placeholders' => true,
		],

		'errorlog' => [
			'driver' => 'errorlog',
			'level' => env('LOG_LEVEL', 'debug'),
			'replace_placeholders' => true,
		],

		'null' => [
			'driver' => 'monolog',
			'handler' => NullHandler::class,
		],

		'emergency' => [
			'path' => storage_path('logs/laravel.log'),
		],
	],
];
