<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Model
use App\Models\Kategori;

// Helper
use App\Helpers\ApiFormatter;

// Validator
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * GET semua kategori
     */
    public function index()
    {
        try {
            $data = Kategori::all();

            if ($data->count() > 0) {
                return ApiFormatter::success(
                    $data,
                    'Data kategori berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Data kategori kosong',
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
     * POST tambah kategori baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'is_active'     => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::error(
                $validator->errors(),
                422
            );
        }

        try {
            $data = Kategori::create([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi'     => $request->deskripsi,
                'is_active'     => $request->is_active ?? true
            ]);

            return ApiFormatter::success(
                $data,
                'Kategori berhasil ditambahkan',
                201
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * GET detail kategori
     */
    public function show($id)
    {
        try {
            $data = Kategori::with('perpustakaan')->find($id);

            if ($data) {
                return ApiFormatter::success(
                    $data,
                    'Detail kategori berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Kategori tidak ditemukan',
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
     * PUT update semua data kategori
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100',
            'deskripsi'     => 'nullable|string',
            'is_active'     => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::error(
                $validator->errors(),
                422
            );
        }

        try {
            $data = Kategori::find($id);

            if (!$data) {
                return ApiFormatter::error(
                    'Kategori tidak ditemukan',
                    404
                );
            }

            $data->update([
                'nama_kategori' => $request->nama_kategori,
                'deskripsi'     => $request->deskripsi,
                'is_active'     => $request->is_active
            ]);

            return ApiFormatter::success(
                $data,
                'Kategori berhasil diperbarui'
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * PATCH update sebagian data kategori
     */
    public function updatePartial(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:100',
            'deskripsi'     => 'sometimes|nullable|string',
            'is_active'     => 'sometimes|required|boolean'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::error(
                $validator->errors(),
                422
            );
        }

        try {
            $data = Kategori::find($id);

            if (!$data) {
                return ApiFormatter::error(
                    'Kategori tidak ditemukan',
                    404
                );
            }

            $data->update($request->only([
                'nama_kategori',
                'deskripsi',
                'is_active'
            ]));

            return ApiFormatter::success(
                $data,
                'Kategori berhasil diperbarui sebagian'
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * DELETE hapus kategori
     */
    public function destroy($id)
    {
        try {
            $data = Kategori::find($id);

            if (!$data) {
                return ApiFormatter::error(
                    'Kategori tidak ditemukan',
                    404
                );
            }

            $data->delete();

            return ApiFormatter::success(
                null,
                'Kategori berhasil dihapus'
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }
}