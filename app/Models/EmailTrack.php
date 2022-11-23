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

    public function track_data()
    {
        return $this->hasMany(EtrackData::class, 'etrackId', 'id');
    }

    public static function getByCaseId($id){

        // $q="select email_tracking.*, ed.event as edevent, ed.url as url, ed.created_at as eddate,ed.timestamp as timestamp,ed.email as edemail  from email_tracking 

        // right join etrack_data ed on email_tracking.id=ed.etrackId

        // where case_id='$id' and casetype='$t' order by email_tracking.created_at asc" ;

        // $result = EmailTrack::select('email_tracking.*', 'ed.event as edevent','ed.url as url', 'ed.created_at as eddate', 'ed.timestamp as timestamp', 'ed.email as edemail')
        //             ->rightJoin('etrack_data as ed', DB::raw('ed.etrackId'), '=', DB::raw('email_tracking.id'))
        //             ->where('case_id', $id)->orderBy('email_tracking.created_at', 'ASC')->get();

                    $result = EmailTrack::select('email_tracking.*', 'ec.title', 'ec.description', 'ed.event as edevent','ed.url as url', 'ed.created_at as eddate', 'ed.timestamp as timestamp', 'ed.email as edemail')
                    ->rightJoin('etrack_data as ed', DB::raw('ed.etrackId'), '=', DB::raw('email_tracking.id'))
                    ->rightJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('email_tracking.event'))
                    ->where('case_id', $id)->orderBy('email_tracking.created_at', 'ASC')->get();

        // return $result=self::find_by_sql($q);
        return $result->groupBy('sg_message_id');

        
    }

    public static function getByCaseIdAndEvent($id, $event, $email){


        $result = EmailTrack::with('track_data')->where('event', $event)->where('email', $email)->where('case_id', $id)->orderBy('id', 'ASC')->limit(1)->first();
        
        // dd($result);
        if(isset($result)) {
            if(count($result['track_data']) == 0) {
                $result = EmailTrack::with('track_data')->where('event', $event)->where('email', $email)->where('case_id', $id)->orderBy('id', 'DESC')->limit(1)->first();
            }
        }
        // dd($id, $event, $email);
        // $result = EmailTrack::where('event', $event)->where('email', $email)->where('case_id', $id)->orderBy('id', 'ASC')->limit(1)->first();

        // if(isset($result)) {
        //     $result = DB::table('etrack_data')->where('etrackId', $result->id)->get();
        // }
        return $result;

        
    }
}
