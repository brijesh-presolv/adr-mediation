<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappTrack extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_tracking';
    public $timestamps = false;

    protected $fillable = [
        'caseid',
        'casetype',
        'event',
        'contact',
        'content',
        'media',
        'request_uuid',
        'credits_charged',
        'full_resp',
    ];
}
