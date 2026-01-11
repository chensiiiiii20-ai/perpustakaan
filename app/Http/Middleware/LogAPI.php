<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Models\ActivityLogModel;

class LogAPI
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = null;

        // Ambil user dari JWT jika ada
        try {
            if ($request->bearerToken()) {
                $user = JWTAuth::parseToken()->authenticate();
                $userId = $user?->id;
            }
        } catch (JWTException $e) {
            $userId = null;
        }

        // Filter request (hapus data sensitif)
        $filteredRequest = ActivityLogModel::filterSensitiveData(
            $request->all()
        );

        // Simpan log request
        $log = ActivityLogModel::create([
            'user_id'    => $userId,
            'action'     => 'API_REQUEST',
            'description'=> json_encode([
                'method'  => $request->method(),
                'url'     => $request->fullUrl(),
                'request' => $filteredRequest
            ]),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Jalankan request ke controller
        $response = $next($request);

        // Simpan response (optional)
        $log->update([
            'action' => 'API_RESPONSE',
            'description' => json_encode([
                'status'   => $response->getStatusCode(),
                'response' => json_decode($response->getContent(), true)
            ])
        ]);

        return $response;
    }
}