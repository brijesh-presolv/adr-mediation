<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappWebhook extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_webhook';
    const UPDATED_AT = null;

    protected $fillable = ['sender', 'sender_profile', 'message_sent', 'total_cost', 'response', 'aread', 'created_at','media'];
}
