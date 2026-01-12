<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpustakaanModel extends Model
{
    use HasFactory;

    // Nama tabel (wajib karena tidak mengikuti konvensi Laravel)
    protected $table = 'perpustakaan';

    // Primary key
    protected $primaryKey = 'id';

    // Primary key auto increment
    public $incrementing = true;

    // Tipe primary key
    protected $keyType = 'int';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'kategori_id',
        'judul_buku',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'stok',
        'is_active'
    ];

    // Jika pakai timestamps (created_at & updated_at)
    public $timestamps = true;
}
