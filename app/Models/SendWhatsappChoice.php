<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SendWhatsappChoice extends Model
{
    use HasFactory;

    protected $table = 'send_whtsapp_choice';

    protected $fillable = [
        'platform_name', 
        'is_active', 
        'updated_at'
    ];
}
