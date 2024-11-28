<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserStopWhatsapp extends Model
{
    use HasFactory;

    protected $table = 'user_stop_whatsapp';
    public $timestamps = false;

    protected $fillable = [
        'message_id', 
        'phone_number', 
        'reply_message', 
        'msgtimestamp', 
        'received_at_utc', 
        'created_at', 
        'updated_at'
    ];
}
