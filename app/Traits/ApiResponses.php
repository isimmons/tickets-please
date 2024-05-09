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
     * @param array|string $errors
     * @param Int|null $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(array|string $errors = [], Int $statusCode = null): JsonResponse
    {
        if(is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors,
        ]);
    }

    protected function notAuthorized(String $message = 'Not Authorized'): JsonResponse
    {
        return $this->errorResponse([
            'message' => $message,
            'status' => 403,
            'source' => ''
        ]);
    }
}
