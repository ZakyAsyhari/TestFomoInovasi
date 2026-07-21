<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponseTrait
{
    /**
     * Send success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @param array $meta
     * @return JsonResponse
     */
    protected function successResponse(
        $data = null,
        string $message = 'Success',
        int $statusCode = Response::HTTP_OK,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $this->getMetaData($statusCode),
        ];

        // Merge custom meta if provided
        if (!empty($meta)) {
            $response['meta'] = array_merge($response['meta'], $meta);
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Send error response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @param array $meta
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = 'Error',
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        $errors = null,
        array $meta = []
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'meta' => $this->getMetaData($statusCode),
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        if (!empty($meta)) {
            $response['meta'] = array_merge($response['meta'], $meta);
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Send validation error response
     *
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    protected function validationErrorResponse(
        $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Send not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function notFoundResponse(
        string $message = 'Resource not found'
    ): JsonResponse {
        return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Send conflict response (for race condition / stock issues)
     *
     * @param string $message
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function conflictResponse(
        string $message = 'Conflict',
        $errors = null
    ): JsonResponse {
        return $this->errorResponse($message, Response::HTTP_CONFLICT, $errors);
    }

    /**
     * Get meta data
     *
     * @param int $statusCode
     * @return array
     */
    private function getMetaData(int $statusCode): array
    {
        return [
            'timestamp' => now()->toISOString(),
            'path' => request()->path(),
            'method' => request()->method(),
            'status_code' => $statusCode,
        ];
    }
}
