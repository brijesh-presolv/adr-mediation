<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class sms_tracking extends Model
{
    use HasFactory;

    

    protected $table = 'sms_tracking';
    public $timestamps = false;
    protected $fillable = [
        'caseid', 'casetype', 'event', 'contact', 'content','jio_tmp' ,'media', 'request_uuid', 'credits_charged', 'is_sent','is_processing','is_success', 'full_resp', 'created_at' ];

    static $datetime_format = 'Y-m-d H:i:s';


    public static function getByCaseId($id){
        // $result = sms_tracking::select('sms_tracking.caseid', 'sms_tracking.request_uuid', 'ss.status')
        //             ->leftJoin('sms_status as ss', DB::raw('sms_tracking.request_uuid'), '=', DB::raw('ss.request_id'))
        //             ->where('sms_tracking.caseid', $id)->orderBy('sms_tracking.created_at', 'ASC')->get();
    
    
        // return $result->groupBy('request_uuid');

        /* comment on 30/06/2025 */
        /*
        $result = DB::table('sms_tracking')
        ->rightJoin('sms_status', 'sms_tracking.request_uuid', '=', 'sms_status.request_id')
        ->select('sms_tracking.*', 'sms_status.status as smstatus','sms_status.status_description as smsdesc', 'sms_status.request_id',DB::raw("CASE
        WHEN sms_status.status='sent' then CONVERT_TZ(sms_status.sent_time,'+00:00','+05:30')
        WHEN sms_status.status='1' then CONVERT_TZ(sms_status.delivered_time,'+00:00','+05:30')
        WHEN sms_status.status='read' then CONVERT_TZ(sms_status.updated_time,'+00:00','+05:30')
         ELSE CONVERT_TZ(sms_status.created_at,'+00:00','+05:30')
      END as smsdate"))
        
        ->where('caseid', $id)
        ->where('casetype', 1)
        ->orderBy('sms_tracking.created_at', 'asc')
        ->get();
        */
        /* comment on 30/06/2025 */


        $result = DB::table('sms_tracking')
        ->rightJoin('sms_status', 'sms_tracking.request_uuid', '=', 'sms_status.request_id')
        ->select('sms_tracking.*', 'sms_status.status as smstatus','sms_status.status_description as smsdesc', 'sms_status.request_id',DB::raw("CASE
        WHEN sms_status.status=1 then CONVERT_TZ(sms_status.delivered_time,'+00:00','+05:30')
        ELSE CONVERT_TZ(sms_status.created_at,'+00:00','+05:30')
        END as smsdate"))
        
        ->where('caseid', $id)
        ->where('casetype', 1)
        ->where('sms_tracking.event', 'ACPTARB_ADM_RES_SMS')
        ->orderBy('sms_tracking.created_at', 'asc')
        ->get();

        echo "<pre>result===>";print_R($result);
        exit;

        return $result ;
    }
}
