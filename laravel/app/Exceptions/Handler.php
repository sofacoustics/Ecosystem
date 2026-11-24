<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
	/**
	 * The list of the inputs that are never flashed to the session on validation exceptions.
	 *
	 * @var array<int, string>
	 */
	protected $dontFlash = [
		'current_password',
		'password',
		'password_confirmation',
		];

		protected $dontReport = [
		];

	/**
	 * Register the exception handling callbacks for the application.
	 */
	public function register(): void
	{
		// 1. REPORTING (Logging)
		$this->reportable(function (Throwable $e) {
			// Skip logging validation/auth failures
			if ($e instanceof \Illuminate\Validation\ValidationException || $e instanceof \Illuminate\Auth\AuthenticationException) {
				return;
			}

			// Check if our custom exception has the reference, otherwise generate one here
			$errorId = method_exists($e, 'getReference') 
				? $e->getReference() 
			  : 'ERR-' . strtoupper(str::random(8));
			//$errorId = 'ERR-' . strtoupper(Str::random(8));
			
			// SAFE CHECK: Prevent silent crashes during Livewire/Console requests
			if (request() && request()->attributes) {
				request()->attributes->set('errorId', $errorId);
			}

			// A. Log a CLEAN message to the ERROR log (No stacktrace)
			Log::error("Exception [{$errorId}]: " . $e->getMessage(), [
					'userId' => auth()->id(), 
			]);

			// B. Log the FULL stacktrace to the DEBUG log
			Log::debug("Stacktrace for [{$errorId}]: " . $e->getMessage(), [
					'exception' => $e,
			]);

			// STOP Laravel from running its default log handler after this.
			return false; 
		});

		// 2. RENDERING (UI Response) - THIS WILL STILL RUN PERFECTLY
		$this->renderable(function (Throwable $e, $request) {
			// Skip custom logic for API requests
			if ($request->is('api/*')) {
				return null;
			}

			// Debug mode (APP_DEBUG=true, dev only!): let Laravel render its detailed error page
			// (message + stacktrace). The error is still logged with its reference code above.
			if (config('app.debug')) {
				return null;
			}

			// If it's an HTTP exception (e.g., 404, 403, 422, 302), ONLY handle it if status code is 500
			if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
				if ($e->getStatusCode() !== 500) {
					return null; // Let Laravel render normal 404, 403, 422, etc.
				}
			}
			// If it's NOT an HTTP exception, check if it's a validation, auth, or redirect exception
			else if (
				$e instanceof \Illuminate\Validation\ValidationException ||
				$e instanceof \Illuminate\Auth\AuthenticationException ||
				$e instanceof \Illuminate\Http\Exceptions\HttpResponseException
			) {
				return null; // Let Laravel handle normal validation redirects/responses
			}

			// If execution reaches here, it is a genuine unhandled 500 PHP crash/Error!
			$errorId = $request->attributes->get('errorId') ?? 'ERR-' . strtoupper(Str::random(8));

			return response()->view('errors.500', [
				'errorId' => $errorId,
				'message' => $e->getMessage(),
			], 500);
		});
	}
}
