<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PerpustakaanModel;
use App\Models\User;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'perpustakaan_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status'
    ];

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ✅ RELASI YANG HILANG (INI PENYEBAB ERROR)
     * Relasi ke buku / perpustakaan
     */
    public function perpustakaan()
    {
        return $this->belongsTo(
            PerpustakaanModel::class,
            'perpustakaan_id',
            'id'
        );
    }
}