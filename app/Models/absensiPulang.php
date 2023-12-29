<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class absensiPulang extends Model
{
    use HasFactory;
    protected $table = 'absensi_pulangs';

    protected $fillable = [
        'user_id',
        'absensi_pulang_time',
        'absensi_pulang_date',
        'qr_code_pulang',
        'longitude_pulang',
        'latitude_pulang',
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
