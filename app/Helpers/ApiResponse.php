<?php

namespace App\Helpers;

class ApiResponse
{
    public static function response($status, $message, $data=null, $token=null)
    {
        if ($token) {
            return response()->json([
                'status_code' => $status,
                'message' => $message,
                'token' => $token,
                'data' => $data
            ], $status);
        }else{
            return response()->json([
                'status_code' => $status,
                'message' => $message,
                'data' => $data
            ], $status);
        }
    }

}
