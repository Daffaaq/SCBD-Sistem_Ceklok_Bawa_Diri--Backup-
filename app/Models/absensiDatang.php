<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class absensiDatang extends Model
{
    use HasFactory;
    protected $table = 'absensi_datangs';

    protected $fillable = [
        'user_id',
        'absensi_datang_time',
        'absensi_datang_date',
        'status_datang',
        'qr_code_datang',
        'longitude_datang',
        'latitude_datang',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
