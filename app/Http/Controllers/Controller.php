<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    /**
     * Собирает положительный json ответ
     *
     * @param array|null $data
     * @param string|null $message
     * @param int $httpCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseSuccess(?array $data, ?string $message, int $httpCode, array $headers = []): JsonResponse
    {
        return response()->json(['status' => 'ok', 'data' => $data, 'message' => $message], $httpCode, $headers);
    }

    /**
     * Собирает отрицательный json ответ
     *
     * @param string $message
     * @param int $httpCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function responseError(string $message, int $httpCode, array $headers = []): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], $httpCode, $headers);
    }
}
