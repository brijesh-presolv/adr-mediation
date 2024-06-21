<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WhatsappBotQue extends Model {
    protected $table = 'whatsapp_bot_que';
    
    protected $fillable = ['caseid', 'bot_type', 'bot_id', 'contact', 'content', 'haptik_tmp', 'variable', 'media', 'casetype', 'event', 'is_sent', 'is_processing', 'is_success']; 
}