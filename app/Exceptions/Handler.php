<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use App\Traits\ApiResponser;
use Barryvdh\Cors\CorsService;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        
        if($exception instanceof ModelNotFoundException) {
           $modelName = strtolower(class_basename($exception->getModel()));

            return response("{$modelName} with the specified identifier could not be found.", 404);
        }
        if ($exception  instanceof AuthorizationException) {
            
            return response($exception->getMessage(), 403);
        }
        if($exception  instanceof NotFoundHttpException) {

            return response('The specified URI could not be found', 404);
        }
        if($exception instanceof MethodNotAllowedHttpException) {
            
            return response('The specified method is invalid.', 405);
        }
        if($exception instanceof HttpException) {
            return response($exception->getMessage(), $exception->getStatusCode());
        }
        if($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

        if($exception instanceof QueryException) {
            return response('Database related error, Check Query and Database connection.', 409);
        }
        if ($exception instanceof AuthenticationException) {
            return response($exception->getMessage(), 403);
        }

       if (env('APP_DEBUG')) {
            return parent::render($request, $exception);
       }

        return parent::render($request, $exception);
    }
}
