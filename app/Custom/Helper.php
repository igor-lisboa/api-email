<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

if (!function_exists('responseApi')) {
    /**
     * Retorno padrao para as respostas de API
     * @param array $data
     * @param bool $success
     * @param string $message
     * @param int $status_code
     * @return JsonResponse
     */
    function responseApi(array $data, bool $success = true, string $message = '', int $status_code = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status_code);
    }
}

if (!function_exists('responseApiException')) {
    /**
     * Retorno padrao para as respostas de API com exceptions
     * @param Exception $e
     * @param array $data
     * @param string $message
     * @param int $status_code
     * @return JsonResponse
     */
    function responseApiException(Exception $e, array $data, string $message, int $status_code = 500): JsonResponse
    {
        Log::error($e->getMessage());

        $exception = [
            'message' => $e->getMessage(),
            'code' => $e->getCode()
        ];

        if (config('app.debug', false)) {
            $exception['trace_as_string'] = $e->getTraceAsString();
            $exception['file'] = $e->getFile();
            $exception['line'] = $e->getLine();
        }

        return responseApi(
            [
                'exception' => $exception,
                'data' => $data
            ],
            false,
            $message,
            $status_code
        );
    }
}
