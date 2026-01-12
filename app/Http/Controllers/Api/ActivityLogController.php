<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Model
use App\Models\ActivityLog;

// Helper
use App\Helpers\ApiFormatter;

class ActivityLogController extends Controller
{
    /**
     * GET semua log aktivitas
     */
    public function index()
    {
        try {
            $data = ActivityLog::with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            if ($data->count() > 0) {
                return ApiFormatter::success(
                    $data,
                    'Data log aktivitas berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Data log aktivitas kosong',
                404
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * GET detail log aktivitas berdasarkan ID
     */
    public function show($id)
    {
        try {
            $data = ActivityLog::with('user')->find($id);

            if ($data) {
                return ApiFormatter::success(
                    $data,
                    'Detail log aktivitas berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Log aktivitas tidak ditemukan',
                404
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * GET log aktivitas user yang sedang login
     */
    public function myLogs()
    {
        try {
            $data = ActivityLog::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();

            if ($data->count() > 0) {
                return ApiFormatter::success(
                    $data,
                    'Log aktivitas user berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Log aktivitas user kosong',
                404
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }
}