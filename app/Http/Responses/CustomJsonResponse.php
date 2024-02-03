<?php

namespace Chatify\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

class CustomJsonResponse extends JsonResponse
{
    /**
     * Send a success response.
     *
     * @param mixed $data
     * @param string|null $message
     * @return CustomJsonResponse
     */
    public static function success($data = [], ?string $message = null): CustomJsonResponse
    {
        return new self([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Send an error response.
     *
     * @param string|null $message
     * @param mixed $errors
     * @return CustomJsonResponse
     */
    public static function error(?string $message = null, $errors = []): CustomJsonResponse
    {
        return new self([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ], 400);
    }
}
