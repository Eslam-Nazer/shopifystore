<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponse
{
    /**
     * Summary of successResponse
     * @param mixed $data
     * @param mixed $message
     * @param mixed $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data, $message = null, $code = 200): JsonResponse
    {
        return response()->json([
            'data'      => $data,
            'message'   => $message,
            'status'    => "Request was Successful."
        ], $code);
    }

    /**
     * Summary of errorResponse
     * @param mixed $data
     * @param mixed $message
     * @param mixed $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($data, $message = null, $code): JsonResponse
    {
        return response()->json([
            "data"      => $data,
            "message"   => $message,
            "status"    => "Error has occurred... "
        ], $code);
    }
}
