<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportImage extends Model
{
    //

    protected $fillable = [
        'guest_report_id',
        'path',
    ];

    public function guestReport (){
        return $this->belongsTo(GuestReport::class);
    }
}
