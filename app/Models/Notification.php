<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notification extends Model {

    use HasFactory;

    protected $table = 'mednotification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_id',
        'event',
        'uploaded_by',
        'mediator_id',
        'user_id',
        'userip',
        'view',
        'view_mediator',
        'view_user'
    ];

    public static function notificationData()
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->orderBy('mednotification.id', 'DESC')->get();

        return $result;
    }

    public static function mediatornotificationData()
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->where('mediator_id', Auth::user()->id)
                    ->orderBy('mednotification.id', 'DESC')->get();

        return $result;
    }

    public static function userNotification()
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->where('view_user', "!=", 2)
                    ->orderBy('mednotification.id', 'DESC')->get();

        return $result;
    }

}
