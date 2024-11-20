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
        'caseid', 'casetype', 'event', 'contact', 'content','jio_tmp' ,'media', 'request_uuid', 'credits_charged', 'is_sent','is_processing','is_success', 'full_resp' ];

    static $datetime_format = 'Y-m-d H:i:s';


    public static function getByCaseId($id){
        $result = sms_tracking::select('sms_tracking.caseid', 'sms_tracking.request_uuid', 'ss.status')
                    ->leftJoin('sms_status as ss', DB::raw('sms_tracking.request_uuid'), '=', DB::raw('ss.request_id'))
                    ->where('sms_tracking.caseid', $id)->orderBy('sms_tracking.created_at', 'ASC')->get();
    
    echo "<pre>";print_R($result);exit;
    }
}
