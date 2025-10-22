<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ReportImage;
class GuestReport extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'location',
        'description',
        'latitude',
        'longitude',
        // 'photo',
        'report_status',
        'fire_status',
    ];

    public function images(){
        return $this->hasMany(ReportImage::class);
    }

}
