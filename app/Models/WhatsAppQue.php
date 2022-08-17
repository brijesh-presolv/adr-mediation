<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WhatsAppQue extends Model {
    protected $table = 'whatsapp_que';
    
    protected $fillable = ['caseid', 'contact', 'content', 'haptik_tmp', 'variable', 'media', 'casetype', 'event', 'is_sent', 'is_processing', 'is_success']; 
}