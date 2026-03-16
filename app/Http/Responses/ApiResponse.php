<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * @param array<string, mixed> $data
     * @param array<int, array<string, mixed>> $errors
     */
    protected function respond(bool $success, string $message, array $data = [], array $errors = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => empty($data) ? (object) [] : $data,
            'errors' => $errors,
        ], $status);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function respondSuccess(string $message, array $data = [], int $status = 200): JsonResponse
    {
        return $this->respond(true, $message, $data, [], $status);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<int, array<string, mixed>> $errors
     */
    protected function respondError(string $message, array $data = [], array $errors = [], int $status = 400): JsonResponse
    {
        return $this->respond(false, $message, $data, $errors, $status);
    }
}
