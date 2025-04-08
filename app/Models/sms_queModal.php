<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sms_queModal extends Model
{
    use HasFactory;

    protected $table = 'sms_que';

    protected $fillable = ['caseid', 'contact', 'content', 'casetype','event', 'jio_tmp', 'ip', 'replylink','created_at', 'is_sent', 'is_processing','notice_type','unq_id_notice'];
}
