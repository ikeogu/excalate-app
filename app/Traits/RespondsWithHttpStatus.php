<?php

namespace App\Traits;

use App\Enums\HttpStatusCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;


trait RespondsWithHttpStatus
{
    /**
     * @param array<mixed> $data
     */
    protected function success(string $message, mixed $data = [], int $status = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function failure(string $message, int $status = 400): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $status);
    }
}
