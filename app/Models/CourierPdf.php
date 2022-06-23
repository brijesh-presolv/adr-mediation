<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierPdf extends Model
{
    use HasFactory;
    protected $table = "courierpdf";
    protected $fillable = ["case_id", "noticeId", "file_name", "type", "status"];
}
