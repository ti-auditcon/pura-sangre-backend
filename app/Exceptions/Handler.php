<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

/**
 * [Handler description]
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\Access\AuthorizationException::class,
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
     *  @param  \Exception  $exception
     *  
     *  @return  void
     *
     *  @throws  \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     *  Render an exception into an HTTP response.
     *
     *  @param   \Illuminate\Http\Request                    $request
     *  @param   \Exception                                  $exception
     *  @return  \Symfony\Component\HttpFoundation\Response
     *
     *  @throws  \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            Session::flash('error', 'No tiene permisos para realizar esta acción');

            return redirect('/');
        }

        if ($exception instanceof PostTooLargeException) {
            return redirect('/')->back();
        }       
        
        return parent::render($request, $exception);
    }
}
