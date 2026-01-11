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
 * Filter data sensitif sebelum disimpan ke log
 *
 * @param array|string|null $data
 * @return array|string|null
 */
public static function filterSensitiveData($data)
{
    if (is_null($data)) {
        return null;
    }

    // Jika data berupa JSON string
    if (is_string($data)) {
        $decoded = json_decode($data, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $data = $decoded;
        } else {
            return $data;
        }
    }

    if (is_array($data)) {
        $sensitiveKeys = [
            'password',
            'token',
            'access_token',
            'authorization',
            'jwt',
            'refresh_token'
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '[FILTERED]';
            }
        }
    }

    return $data;
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
