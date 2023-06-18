<?php
namespace App\Exception;

use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Throwable\HttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Throwable\NotFoundHttpException;

/**
 * Our general exception handler
 * @package app\Throwable
 */
final class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report our exception. If in testing with verbosity, it will also dump exception information to the console
     * @throws Throwable
     */
    public function report(Throwable $exception): void
    {
        if (App::environment() == 'testing' && env('TESTING_VERBOSE')) {
            // If we're verbose, we should print ALL Exceptions to the screen
            print($exception->getMessage() . "\n");
            print($exception->getFile() . ": Line: " . $exception->getLine() . "\n");
            print($exception);
        }
        parent::report($exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->mediumText(['error' => 'Unauthenticated.'], 401);
        }
        return redirect()->guest('login');
    }

    /**
     * Convert the given exception to an array.
     * @note This is overriding Laravel's default exception handler in order to handle binary data in message
     */
    protected function convertExceptionToArray(Throwable $e): array
    {
        return config('app.debug') ? [
            'message' => utf8_encode($e->getMessage()),
            'exception' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(fn($trace): array => Arr::except($trace, ['args']))->all(),
        ] : [
            'message' => $this->isHttpException($e) ? $e->getMessage() : 'Server Error',
        ];
    }


}
