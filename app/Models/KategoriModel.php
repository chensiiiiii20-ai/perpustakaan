<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    protected $fillable = ['nama_kategori'];

    public function perpustakaan ()
    {
        return $this->hasMany(PerpustakaanModel::class, 'category_id');
    }
}
