<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_log';

    const UPDATED_AT = null;


    protected $fillable = ['request_id', 'created_time', 'sent_time', 'delivered_time', 'updated_time', 'status', 'response', 'created_at'];
}
