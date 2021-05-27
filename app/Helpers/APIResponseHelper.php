<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class APIResponseHelper
{
    public static function success(array $data = []): Response
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public static function failed(
        string $message = '',
        int $status = Response::HTTP_BAD_REQUEST
    ): Response {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
            ],
            $status
        );
    }
}
