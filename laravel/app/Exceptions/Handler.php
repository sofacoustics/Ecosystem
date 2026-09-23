<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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

			$errorId = 'ERR-' . strtoupper(Str::random(8));
			request()->attributes->set('errorId', $errorId);

			logger()->error("Exception [{$errorId}]: " . $e->getMessage(), [
				'exception' => $e,
			]);
		});

		// 2. RENDERING (UI Response)
		$this->renderable(function (Throwable $e, $request) {
			// Skip custom logic for API requests
			if ($request->is('api/*')) {
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
