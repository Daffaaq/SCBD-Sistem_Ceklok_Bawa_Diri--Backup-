<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendence extends Model
{
    use HasFactory;
    protected $table = 'attendences';

    protected $fillable = [
        'user_id',
        'attendences_time',
        'attendences_date',
        'attendance_status',
        'attendance_type',
        'attendences_Status',
        'file',
        'longitude_attendences',
        'latitude_attendences',
        'created_by'
    ];

    // Relasi dengan model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
