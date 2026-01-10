<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Panggil Model
use App\Models\PerpustakaanModel;

// Panggil Helper API Formatter
use App\Helpers\ApiFormatter;

// Panggil Validator
use Illuminate\Support\Facades\Validator;

class PerpustakaanController extends Controller
{
    public function index()
{
    try {
        $data = PerpustakaanModel::all();

        if ($data->count() > 0) {
            return ApiFormatter::success(
                $data,
                'Data perpustakaan berhasil diambil'
            );
        }

        return ApiFormatter::error(
            'Data perpustakaan kosong',
            404
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}
public function store(Request $request)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'judul_buku'   => 'required|string|max:255',
        'penulis'      => 'required|string|max:255',
        'penerbit'     => 'nullable|string|max:255',
        'tahun_terbit' => 'nullable|digits:4|integer',
        'stok'         => 'required|integer|min:0',
        'is_active'    => 'nullable|boolean'
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return ApiFormatter::error(
            $validator->errors(),
            422
        );
    }

    try {
        // Simpan data ke database
        $data = PerpustakaanModel::create([
            'judul_buku'   => $request->judul_buku,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => $request->stok,
            'is_active'    => $request->is_active ?? true
        ]);

        return ApiFormatter::success(
            $data,
            'Data perpustakaan berhasil ditambahkan',
            201
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}
public function show($id)
{
    try {
        $data = PerpustakaanModel::find($id);

        if ($data) {
            return ApiFormatter::success(
                $data,
                'Detail data perpustakaan berhasil diambil'
            );
        }

        return ApiFormatter::error(
            'Data perpustakaan tidak ditemukan',
            404
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}
public function update(Request $request, $id)
{
    // Validasi input
    $validator = Validator::make($request->all(), [
        'judul_buku'   => 'required|string|max:255',
        'penulis'      => 'required|string|max:255',
        'penerbit'     => 'nullable|string|max:255',
        'tahun_terbit' => 'nullable|digits:4|integer',
        'stok'         => 'required|integer|min:0',
        'is_active'    => 'required|boolean'
    ]);

    // Jika validasi gagal
    if ($validator->fails()) {
        return ApiFormatter::error(
            $validator->errors(),
            422
        );
    }

    try {
        // Cari data
        $data = PerpustakaanModel::find($id);

        if (!$data) {
            return ApiFormatter::error(
                'Data perpustakaan tidak ditemukan',
                404
            );
        }

        // Update data
        $data->update([
            'judul_buku'   => $request->judul_buku,
            'penulis'      => $request->penulis,
            'penerbit'     => $request->penerbit,
            'tahun_terbit' => $request->tahun_terbit,
            'stok'         => $request->stok,
            'is_active'    => $request->is_active
        ]);

        return ApiFormatter::success(
            $data,
            'Data perpustakaan berhasil diperbarui'
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}
public function updatePartial(Request $request, $id)
{
    // Validasi hanya field yang dikirim
    $validator = Validator::make($request->all(), [
        'judul_buku'   => 'sometimes|required|string|max:255',
        'penulis'      => 'sometimes|required|string|max:255',
        'penerbit'     => 'sometimes|nullable|string|max:255',
        'tahun_terbit' => 'sometimes|nullable|digits:4|integer',
        'stok'         => 'sometimes|required|integer|min:0',
        'is_active'    => 'sometimes|required|boolean'
    ]);

    if ($validator->fails()) {
        return ApiFormatter::error(
            $validator->errors(),
            422
        );
    }

    try {
        // Cari data
        $data = PerpustakaanModel::find($id);

        if (!$data) {
            return ApiFormatter::error(
                'Data perpustakaan tidak ditemukan',
                404
            );
        }

        // Update hanya field yang dikirim
        $data->update($request->only([
            'judul_buku',
            'penulis',
            'penerbit',
            'tahun_terbit',
            'stok',
            'is_active'
        ]));

        return ApiFormatter::success(
            $data,
            'Data perpustakaan berhasil diperbarui sebagian'
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}
public function destroy($id)
{
    try {
        // Cari data
        $data = PerpustakaanModel::find($id);

        if (!$data) {
            return ApiFormatter::error(
                'Data perpustakaan tidak ditemukan',
                404
            );
        }

        // Hapus data
        $data->delete();

        return ApiFormatter::success(
            null,
            'Data perpustakaan berhasil dihapus'
        );

    } catch (\Exception $e) {
        return ApiFormatter::error(
            $e->getMessage(),
            500
        );
    }
}

}
