<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WhatsappChatbot extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_chatbot';
    public $timestamps = false;

    protected $fillable = ['phone_number', 'message_id', 'type', 'chat_message_type', 'message_status', 'received_at_utc', 'message_content_type', 'message', 'reply_from', 'reply_message_id', 'reponse_file_path', 'timestamp', 'is_send', 'updated_at'];
}
