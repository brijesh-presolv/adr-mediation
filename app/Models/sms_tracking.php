<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sms_tracking extends Model
{
    use HasFactory;

    

    protected $table = 'sms_tracking';
    public $timestamps = false;
    protected $fillable = [
        'caseid', 'casetype', 'event', 'contact', 'content','jio_tmp' ,'media', 'request_uuid', 'credits_charged', 'is_sent','is_processing','is_success', 'full_resp' ];

    static $datetime_format = 'Y-m-d H:i:s';
}
