<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses {

    /**
     * Returns a successful JsonResponse with message and 200 status code
     * @param String $message
     * @param array $data
     * @param Int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(String $message, array $data = [], Int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

    /**
     * Returns an error JsonResponse with message and status code
     * @param String $message
     * @param Int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(String $message, Int $statusCode): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
