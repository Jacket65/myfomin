<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(
            except: [
                'debug/*',
                'api/*',
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed.',
                    'detail' => collect($e->errors())->flatten()->first() ?? 'Invalid data provided.',
                ], 422);
            }
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Not Found.',
                    'detail' => 'The requested resource could not be found.',
                ], 404);
            }
        });

        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access denied.',
                    'detail' => 'You do not have permission to perform this action.',
                ], 403);
            }
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Server error.',
                    'detail' => config('app.debug')
                        ? $e->getMessage()
                        : 'An unexpected error occurred. Please try again later.',
                ], 500);
            }
        });
    })
    ->create();
