<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OtpCode extends Model
{
    use HasFactory;
    protected $table = 'otp_code';
    public $timestamps = false;

    protected $fillable = [
        'otp',
        'caseid',
        'mobileNo',
        'email',
        'status',
        'offer_id',
        'is_verified',
        'otp_event',
        'Expire_date',
        'created_at',
        'updated_at'
    ];

}