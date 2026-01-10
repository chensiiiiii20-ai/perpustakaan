<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Model User
use App\Models\User;

// Helper API Formatter
use App\Helpers\ApiFormatter;

// Validator
use Illuminate\Support\Facades\Validator;

// Hash password
use Illuminate\Support\Facades\Hash;

// JWT Auth
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'    => 'required|email',
        'password' => 'required|string|min:6'
    ]);

    if ($validator->fails()) {
        return ApiFormatter::error(
            $validator->errors(),
            422
        );
    }

    $credentials = $request->only('email', 'password');

    try {
        // 🔥 WAJIB PAKAI auth('api')
        if (! $token = auth('api')->attempt($credentials)) {
            return ApiFormatter::error(
                'Email atau password salah',
                401
            );
        }

        return ApiFormatter::success([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60,
            'user'         => auth('api')->user()
        ], 'Login berhasil');

    } catch (\Exception $e) {
        return ApiFormatter::error(
            'Tidak dapat membuat token',
            500
        );
    }
}
public function me()
{
    try {
        // Ambil user dari token JWT
        $user = auth()->user();

        if (!$user) {
            return ApiFormatter::error(
                'User tidak terautentikasi',
                401
            );
        }

        return ApiFormatter::success(
            $user,
            'Data user berhasil diambil'
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}
public function refresh()
{
    try {
        // Refresh token JWT
        $newToken = auth('api')->refresh();

        return ApiFormatter::success([
            'access_token' => $newToken,
            'token_type'   => 'Bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ], 'Token berhasil diperbaharui');

    } catch (\Exception $e) {
        return ApiFormatter::error(
            'Token tidak dapat diperbaharui',
            401
        );
    }
}
public function logout()
{
    try {
        // Invalidate token (logout)
        auth('api')->logout();

        return ApiFormatter::success(
            null,
            'Logout berhasil, token telah dihapus'
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            'Logout gagal',
            500
        );
    }
}

}
