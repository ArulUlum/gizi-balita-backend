<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    public function successResponse($message, $data = [])
    {
        $response = [
            "code" => 200,
            "message" => $message,
            "data" => $data
        ];

        return response()->json($response, 200);
    }

    public function errorValidationResponse($error, $errorMessages = [])
    {
        $code = 400;
        $response = [
            "code" => $code,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function errorUnauthorizedResponse($error, $errorMessages = [])
    {
        $code = 401;
        $response = [
            "code" => $code,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    public function errorNotFound($error, $errorMessages = [])
    {
        $code = 404;
        $response = [
            "code" => $code,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
