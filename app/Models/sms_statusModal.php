<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sms_statusModal extends Model
{
    use HasFactory;

    protected $table = 'sms_status';

    protected $fillable = ['request_id', 'created_time', 'sent_time','delivered_time', 'updated_time', 'status','created_at'];
}
