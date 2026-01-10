<?php

namespace App\Helpers;

class ApiFormatter
{
    /**
     * Response sukses
     */
    public static function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'status'  => 'success',
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ], $code);
    }

    /**
     * Response error
     */
    public static function error($message = 'Error', $code = 400, $data = null)
    {
        return response()->json([
            'status'  => 'error',
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ], $code);
    }
}
