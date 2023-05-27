<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Response Success
     *
     * @param mixed $data
     * @param string $message
     * @param integer $statusCode
     * @return JsonResponse
     */
    public function success(
        mixed $data,
        string $message = "Success",
        int $statusCode = 200
    ): JsonResponse {
        return response()->json(
            [
                "data" => $data,
                "success" => true,
                "message" => $message,
            ],
            $statusCode
        );
    }

    /**
     * Response Error
     *
     * @param string $message
     * @param integer $statusCode
     * @return JsonResponse
     */
    public function error(string $message, int $statusCode): JsonResponse
    {
        return response()->json(
            [
                "data" => null,
                "success" => false,
                "message" => $message,
            ],
            $statusCode
        );
    }
}
