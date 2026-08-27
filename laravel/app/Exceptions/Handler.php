<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
				});

				// Add custom 500 error rendering logic here
        $this->renderable(function (Throwable $e, $request) {
            // Skip custom rendering for API requests or non-500 HTTP exceptions (e.g., 404, 403)
            if ($request->is('api/*') || ($e instanceof HttpExceptionInterface && $e->getStatusCode() !== 500)) {
                return null; // Fallback to standard Laravel handling
            }
						$errorId = 'ERR-' . strtoupper(Str::random(8));

						app('log')->debug("app/Exceptions/Handler.php register() function");

            // Log exception alongside the error reference ID
            logger()->error("Exception [{$errorId}]: " . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Render resources/views/errors/500.blade.php with data
            return response()->view('errors.500', [
                'errorId' => $errorId,
                'message' => $e instanceof HttpExceptionInterface ? $e->getMessage() : null,
						], 500);

        });
    }
}
