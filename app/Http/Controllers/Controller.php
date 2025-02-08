<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function sendResponse($result, $message = null, $code = 200)
    {
        return Response::json([
            'data' => $result,
            'message' => $message,
        ], $code);
    }

    /**
     * @param  null  $message
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendErrorResponse($errors, $message = null, $code = 422)
    {
        return Response::json([
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    public function sendError($error, $code = 422)
    {
        return Response::json([
            'success' => false,
            'message' => $error,
        ], $code);
    }

    /**
     * @param  string  $message
     * @return JsonResponse
     */
    public function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    /**
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendNotFoundResponse($message, $code = 404)
    {
        return Response::json([
            'message' => $message,
            'status' => $code,
        ], $code);
    }
}
