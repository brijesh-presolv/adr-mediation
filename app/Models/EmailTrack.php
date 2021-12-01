<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailTrack extends Model
{
    use HasFactory;

    protected $table = 'email_tracking';
    public $timestamps = false;

    protected $fillable = [
        'case_id',
        'userid',
        'event',
        'email',
        'sg_message_id',
        'status',
    ];

    public static function getByCaseId($id){

        // $q="select email_tracking.*, ed.event as edevent, ed.url as url, ed.created_at as eddate,ed.timestamp as timestamp,ed.email as edemail  from email_tracking 

        // right join etrack_data ed on email_tracking.id=ed.etrackId

        // where case_id='$id' and casetype='$t' order by email_tracking.created_at asc" ;

        $result = EmailTrack::select('email_tracking.*', 'ed.event as edevent','ed.url as url', 'ed.created_at as eddate', 'ed.timestamp as timestamp', 'ed.email as edemail')
                    ->rightJoin('etrack_data as ed', DB::raw('ed.etrackId'), '=', DB::raw('email_tracking.id'))
                    ->where('case_id', $id)->orderBy('email_tracking.created_at', 'ASC')->get();

        // return $result=self::find_by_sql($q);
        return $result;

        
    }
}
