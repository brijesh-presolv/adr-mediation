<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class sms_template extends Model
{
    use HasFactory;

    protected $table='sms_template';

    static function getsmscontent($n) {
        $query = sms_template::select('content','DLT_TE_ID')->where('name', $n)->first();
        return $query;

        // return  str_replace(['::',';;'], ['‘','’'],$query['content']);
    }

    static function getsmscontent1($n) {
       
        $query = sms_template::select('content','DLT_TE_ID')->where('jio_template', $n)->first();
        return $query;

    }
}
