<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLogModel extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];

    protected $attributes = [
        'user_id'    => null,
        'description'=> null,
        'ip_address' => null,
        'user_agent' => null
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ TAMBAHKAN INI
    public static function filterSensitiveData(array $data)
    {
        $sensitiveFields = ['password', 'password_confirmation', 'token'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '******';
            }
        }

        return $data;
    }
}