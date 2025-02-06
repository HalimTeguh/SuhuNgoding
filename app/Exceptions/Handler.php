<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // Handling NotFoundHttpException (URL Not Found)
        if ($exception instanceof NotFoundHttpException) {
            // Jika halaman tidak ditemukan, arahkan ke halaman notFound atau maintenance
            // if (config('app.env') === 'production') {
            // }
            return redirect()->route('maintenance');  // Mengarahkan ke halaman notFound
            
            // Untuk pengembangan (local), gunakan 404 yang standar atau kustom
            // return response()->view('errors.404', [], 404);
        }

        return parent::render($request, $exception);
    }
}
