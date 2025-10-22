<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FireArea extends Model
{
    //
    use HasFactory;

    protected $table = 'fire_areas';
    protected $fillable = [
        'alamat',
        'jenis_ikon',
        'latitude',
        'longitude',
        'tanggal_kejadian',
    ];
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'tanggal_kejadian' => 'date', // Otomatis konversi ke objek Carbon/Date
    ];
}
