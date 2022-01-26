<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
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
        if ($exception instanceof AuthorizationException) {
            Session::flash('error','No tiene permisos para realizar esta accion');
            return redirect('/');
        }
        
        if ($exception instanceof PostTooLargeException) {
            return redirect('/')->back();
        }       

        if ($exception instanceof ValidationException && !$this->isFrontend(request())) {
            return response()->json([
                'message' => 'Los siguientes datos son inválidos',
                'errors'  => $exception->validator->getMessageBag()
            ], 422);
        }

        return parent::render($request, $exception);
    }

    protected function whoopsHandler()
    {
        try {
            return app(\Whoops\Handler\HandlerInterface::class);
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            return parent::whoopsHandler();
        }
    }

    /**
     *  [isFrontend description]
     *
     *  @param   [type]  $request  [$request description]
     *
     *  @return  [type]            [return description]
     */
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
