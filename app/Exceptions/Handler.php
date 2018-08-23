<?php

namespace App\Exceptions;

use Exception;
use Asm89\Stack\CorsService;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
        $response = $this->handleException($request, $exception);

        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }

    /**
     * [handleException description]
     * @param  [type]    $request   [description]
     * @param  Exception $exception [description]
     * @return [type]               [description]
     */
    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof ValidationException){
        return $this->convertValidationExceptionToResponse($exception, $request);
      }

      if ($exception instanceof ModelNotFoundException){
        $modelo = strtolower(class_basename($exception->getModel()));

        return $this->errorResponse("No existe ninguna instancia de {$modelo} con el parametro especificado", 404);
      }

      if ($exception instanceof AuthenticationException){
        return $this->unauthenticated($request, $exception);
      }

      if ($exception instanceof AuthorizationException){
        return $this->errorResponse('No esta autorizado', 403);
      }

      if ($exception instanceof NotFoundHttpException){
        return $this->errorResponse('No se encontro la URL especificada', 404);
      }

      if ($exception instanceof MethodNotAllowedHttpException){
        return $this->errorResponse('El metodo especificado en la peticion no es valido', 405);
      }

      if ($exception instanceof HttpException){
        return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
      }

      if ($exception instanceof QueryException){
        $codigo = $exception->errorInfo[1];

        if ($codigo == 1451) {
          return $this->errorResponse('No se puede eliminar por que el recurso esta relacionado con otro', 409);
        }

      }

      if ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        }

      if (config('app.debug')) {
        return parent::render($request, $exception);
      }

      return $this->errorResponse('Falla inesperada, intente nuevamente, o reintente mas tarde', 500);

    }

    /**
     * [convertValidationExceptionToResponse: convierte el error en una respuesta json]
     * @param  ValidationException $e       [description]
     * @param  [type]              $request [description]
     * @return [type]                       [description]
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        if ($this->isFrontend($request)) {
            return $request->ajax() ? response()->json($errors, 422) : redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
    }

    /**
     * [isFrontend: método para saber si la petición viene de la web o no]
     * @param  [type]  $request [description]
     * @return boolean          [description]
     */
    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    /**
     * [errorResponse description]
     * @param  [type] $message [description]
     * @param  [type] $code    [description]
     * @return [type]          [description]
     */
    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    /**
     * [unauthenticated description]
     * @param  [type]                  $request   [description]
     * @param  AuthenticationException $exception [description]
     * @return [type]                             [description]
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($this->isFrontend($request)) {
            return redirect()->guest('login');
        }

        return $this->errorResponse('No autenticado', 401);
    }
}
