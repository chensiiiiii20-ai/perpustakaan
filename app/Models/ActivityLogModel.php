<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogModel extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'activity_logs';

    /**
     * Primary key
     */
    protected $primaryKey = 'id';

    /**
     * Nonaktifkan updated_at
     * (karena tabel log biasanya hanya pakai created_at)
     */
    public $timestamps = false;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    /**
     * Default value otomatis
     */
    protected $attributes = [
        'user_id'    => null,
        'description'=> null,
        'ip_address' => null,
        'user_agent' => null
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}