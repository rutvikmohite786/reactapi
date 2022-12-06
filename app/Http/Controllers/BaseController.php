<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    public function apiSuccess($message, $statusCode, $data = '', $extraData = [])
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'extraData' => $extraData
        ], $statusCode);
    }

    public function apiFail($message, $statusCode, $data = null)
    {    
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
}