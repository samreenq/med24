<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($request->is("api/*")) {
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 0,
                    'message' => $exception->getMessage(),
                    'api_status'=>200,
                    'data' =>null
                ]);
            }elseif ($exception instanceof ValidationException){
                return response()->json([
                    'status' => 0,
                    'api_status'=>200,

                    'message' => $exception->validator->errors()->all()[0],
                    'data' => null
                ]);
            }
            elseif ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'status' => 0,
                    'api_status'=>200,
                    'message' => 'Wrong http method given',
                    'data' => null
                ]);
            }elseif ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 0,
                    'api_status'=>200,
                    'message' => 'Given URL not found on server',
                    'data' => null
                ]);
            }elseif($exception instanceof  AuthenticationException){
                return response()->json([
                    'status' => 0,
                    'api_status'=>401,
                    'message' => $exception->getMessage(),
                    'data' =>null
                ]);
            }
            else{
                return response()->json([
                    'status' => 0,
                    'api_status'=>200,
                    'message' => $exception->getMessage().' line no '.$exception->getLine().' file '.$exception->getFile(),
                    'data' => null
                ]);
            }
        }else{
            return parent::render($request, $exception);
        }
        // if($exception instanceof \Illuminate\Auth\AuthenticationException ){
        //     return response()->json([
        //         'message' => 'Unauthorized',
        //         'status' => 0
        //     ], 401);
        // }
        return parent::render($request, $exception);
    }
}
