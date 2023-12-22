<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CaseReply extends Model
{
    use HasFactory;
    protected $table = 'case_reply';
    public $timestamps = false;

    protected $fillable = [
        'caseid',
        'organization_name',
        'party_name',
        'your_reply',
        'attachment_file',
        'reply_data',
        'case_pdf',
        'reply_on'
    ];

}