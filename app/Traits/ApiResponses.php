<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses {

    /**
     * Calls the successResponse method with message and 200 status code
     * @param String $message
     * @return JsonResponse
     */
    protected function ok(String $message): JsonResponse
    {
        return $this->successResponse($message, 200);
    }

    /**
     * Returns a successful JsonResponse with message and 200 status code
     * @param String $message
     * @param Int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(String $message, Int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
