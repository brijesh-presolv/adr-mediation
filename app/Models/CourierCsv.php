<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierCsv extends Model
{
    use HasFactory;
    protected $table = "couriercsv";
    protected $fillable = ["case_id", "noticeId", "awb_no", "status_as_on_date", "status_at", "last_activity", "reason", "final_status", "type", "status", 'pdf_uploaded'];

    // public function pdf()
    // {
    //     return $this->hasOne(CourierPdf::class, "noticeId", "noticeId");
    // }
}
