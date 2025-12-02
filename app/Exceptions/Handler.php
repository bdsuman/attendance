<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // If the framework wrapped a ModelNotFoundException inside a
        // NotFoundHttpException (route model binding), prefer the model
        // message so API clients get e.g. "Shift not found" instead of
        // a generic "Route not found".
        if ($e instanceof NotFoundHttpException) {
            $prev = $e->getPrevious();

            if ($prev instanceof ModelNotFoundException) {
                $model = class_basename($prev->getModel());
                $message = sprintf('%s not found', $model);

                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'message' => $message,
                        'status' => 404,
                    ], 404);
                }

                abort(404, $message);
            }

            // Some Laravel versions throw a RouteNotFoundException class.
            $routeClass = 'Illuminate\\Routing\\Exceptions\\RouteNotFoundException';
            $isRouteNotFound = class_exists($routeClass) && is_a($e, $routeClass);

            $message = $isRouteNotFound ? 'Route not found' : ($e->getMessage() ?: 'Not Found');

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 404,
                ], 404);
            }

            abort(404, $message);
        }

        // Direct ModelNotFoundException (not wrapped)
        if ($e instanceof ModelNotFoundException) {
            $model = class_basename($e->getModel());
            $message = sprintf('%s not found', $model);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'status' => 404,
                ], 404);
            }

            abort(404, $message);
        }

        return parent::render($request, $e);
    }
}
