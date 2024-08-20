<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    public function render($request, Throwable $exception): \Illuminate\Http\JsonResponse
    {
        // Handle validation errors separately
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation Error',
                'errors' => $exception->errors(),
                'code' => 422
            ], 422);
        }

        // Default error response
        return response()->json([
            'status' => 'error',
            'message' => $exception->getMessage() ?: 'Something went wrong',
            'code' => $exception->getCode() ?: 500
        ], $exception->getCode() ?: 500);
    }
}
