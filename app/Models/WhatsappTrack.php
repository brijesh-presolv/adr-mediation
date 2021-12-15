<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getByCaseIdWh($id)
    {

        // $q = "select whatsapp_tracking.*, wl.status as wlstatus,wl.created_at as wldate,wl.request_id from whatsapp_tracking 
   
        //  right join whatsapp_log wl on whatsapp_tracking.request_uuid=wl.request_id
   
        //  where caseid='$id' order by whatsapp_tracking.created_at asc";

         $result = WhatsappTrack::select('whatsapp_tracking.*', 'ec.title', 'ec.whdescription', 'wl.status as wlstatus','wl.created_at as wldate','wl.request_id')
                    ->rightJoin('whatsapp_log as wl', DB::raw('wl.request_id'), '=', DB::raw('whatsapp_tracking.request_uuid'))
                    ->rightJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('whatsapp_tracking.event'))
                    ->where('caseid', $id)->orderBy('whatsapp_tracking.created_at', 'ASC')->get();

        return $result;
    }
}
