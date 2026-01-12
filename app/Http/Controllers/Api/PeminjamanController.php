<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Models
use App\Models\Peminjaman;
use App\Models\PerpustakaanModel;

// Helper
use App\Helpers\ApiFormatter;

// Validator
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    /**
     * GET semua data peminjaman
     */
    public function index()
    {
        try {
            $data = Peminjaman::with(['user', 'perpustakaan'])->get();

            if ($data->count() > 0) {
                return ApiFormatter::success(
                    $data,
                    'Data peminjaman berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Data peminjaman kosong',
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
     * POST peminjaman buku
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perpustakaan_id' => 'required|exists:perpustakaan,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali'=> 'nullable|date|after_or_equal:tanggal_pinjam'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::error(
                $validator->errors(),
                422
            );
        }

        try {
            $buku = PerpustakaanModel::find($request->perpustakaan_id);

            // Cek stok
            if ($buku->stok < 1) {
                return ApiFormatter::error(
                    'Stok buku habis',
                    400
                );
            }

            // Simpan peminjaman
            $data = Peminjaman::create([
                'user_id'         => auth()->id(),
                'perpustakaan_id' => $request->perpustakaan_id,
                'tanggal_pinjam'  => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
                'status'          => 'dipinjam'
            ]);

            // Kurangi stok
            $buku->decrement('stok');

            return ApiFormatter::success(
                $data,
                'Peminjaman buku berhasil',
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
     * GET detail peminjaman
     */
    public function show($id)
    {
        try {
            $data = Peminjaman::with(['user', 'perpustakaan'])->find($id);

            if ($data) {
                return ApiFormatter::success(
                    $data,
                    'Detail peminjaman berhasil diambil'
                );
            }

            return ApiFormatter::error(
                'Data peminjaman tidak ditemukan',
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
     * PUT pengembalian buku
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kembali' => 'required|date',
            'status'          => 'required|in:dipinjam,dikembalikan'
        ]);

        if ($validator->fails()) {
            return ApiFormatter::error(
                $validator->errors(),
                422
            );
        }

        try {
            $data = Peminjaman::find($id);

            if (!$data) {
                return ApiFormatter::error(
                    'Data peminjaman tidak ditemukan',
                    404
                );
            }

            // Jika status dikembalikan → tambah stok
            if ($data->status === 'dipinjam' && $request->status === 'dikembalikan') {
                PerpustakaanModel::find($data->perpustakaan_id)->increment('stok');
            }

            $data->update([
                'tanggal_kembali' => $request->tanggal_kembali,
                'status'          => $request->status
            ]);

            return ApiFormatter::success(
                $data,
                'Data peminjaman berhasil diperbarui'
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * DELETE data peminjaman
     */
    public function destroy($id)
    {
        try {
            $data = Peminjaman::find($id);

            if (!$data) {
                return ApiFormatter::error(
                    'Data peminjaman tidak ditemukan',
                    404
                );
            }

            $data->delete();

            return ApiFormatter::success(
                null,
                'Data peminjaman berhasil dihapus'
            );

        } catch (\Exception $e) {
            return ApiFormatter::error(
                $e->getMessage(),
                500
            );
        }
    }
}