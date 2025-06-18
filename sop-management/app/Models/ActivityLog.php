<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'document_id',
        'ip_address',
    ];

    // Tambahkan ini agar relasi ke user bisa dipakai
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
