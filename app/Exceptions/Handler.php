<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use App\Helpers\ApiFormatter;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render exception into JSON response (API ONLY)
     */
    public function render($request, Throwable $exception)
    {
        /* =========================
         * ROUTE TIDAK DITEMUKAN (404)
         * ========================= */
        if (
            $exception instanceof NotFoundHttpException ||
            $exception instanceof RouteNotFoundException
        ) {
            return ApiFormatter::error(
                'Endpoint tidak ditemukan',
                404
            );
        }

        /* =========================
         * DATA MODEL TIDAK DITEMUKAN
         * ========================= */
        if ($exception instanceof ModelNotFoundException) {
            return ApiFormatter::error(
                'Data tidak ditemukan',
                404
            );
        }

        /* =========================
         * VALIDATION ERROR (422)
         * ========================= */
        if ($exception instanceof ValidationException) {
            return ApiFormatter::error(
                $exception->errors(),
                422
            );
        }

        /* =========================
         * AUTH / JWT ERROR (401)
         * ========================= */
        if ($exception instanceof AuthenticationException) {
            return ApiFormatter::error(
                'Token tidak valid, kadaluarsa, atau belum login',
                401
            );
        }

        /* =========================
         * METHOD SALAH (405)
         * ========================= */
        if ($exception instanceof MethodNotAllowedHttpException) {
            return ApiFormatter::error(
                'Method HTTP tidak diizinkan pada endpoint ini',
                405
            );
        }

        /* =========================
         * ERROR UMUM (500)
         * ========================= */
        return ApiFormatter::error(
            $exception->getMessage(),
            500
        );
    }
}