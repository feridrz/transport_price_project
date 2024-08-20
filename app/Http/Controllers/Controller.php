<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function successResponse($data, $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], $statusCode);
    }

    protected function errorResponse($message, $statusCode): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'code' => $statusCode,
        ], $statusCode);
    }
}
