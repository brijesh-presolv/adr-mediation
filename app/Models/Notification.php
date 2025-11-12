<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notification extends Model {

    use HasFactory;

    const CATEGORY_CASE_UPDATES= 1;
    const CATEGORY_DOCUMENTS= 2;
    const CATEGORY_SESSION= 3;
    const CATEGORY_NEW_Account= 4;

    protected $table = 'mednotification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_id',
        'reg_id',
        'event',
        'uploaded_by',
        'mediator_id',
        'user_id',
        'userip',
        'view',
        'view_mediator',
        'view_user',
        'category',
        'action_type'
    ];

    public static function notificationData()
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription' ,'users.email')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->leftJoin('users', DB::raw('users.id'), '=', DB::raw('mednotification.reg_id'))
                    ->orderBy('mednotification.id', 'DESC')->get();

        return $result;
    }

    public static function mediatornotificationData()
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->where('mednotification.mediator_id', Auth::user()->id)
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

    public static function mediatornotificationDataAPI($userId)
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->where('mednotification.mediator_id', $userId)
                    ->orderBy('mednotification.id', 'DESC')->get();

        return $result;
    }

    public static function notificatioLatestData()
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription' ,'users.email')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->leftJoin('users', DB::raw('users.id'), '=', DB::raw('mednotification.reg_id'))
                    ->orderBy('mednotification.id', 'DESC')->limit(5)->get();

        return $result;
    }

    public static function notificationDatabyctgry($category, $start, $length, $sortOrder)
    {

         $query = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription' ,'users.email')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->leftJoin('users', DB::raw('users.id'), '=', DB::raw('mednotification.reg_id'));

                if (!empty($category)) {
                    $query->where('mednotification.category', $category);
                }

                if (!empty($sortOrder)) {
                    $query->orderBy('mednotification.id', $sortOrder);
                } else {
                    $query->orderBy('mednotification.id', 'DESC');
                }

        $result = $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
        return $result;
    }
    public static function mediatornotificationbyctgry($userId, $category, $start, $length, $sortOrder)
    {

         $query = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->where('mednotification.mediator_id', $userId);


                if (!empty($category)) {
                    $query->where('mednotification.category', $category);
                }

                if (!empty($sortOrder)) {
                    $query->orderBy('mednotification.id', $sortOrder);
                } else {
                    $query->orderBy('mednotification.id', 'DESC');
                }

        $result = $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
        return $result;
    }

    public static function usernotificationAPI($userId)
    {

         $result = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->leftJoin('user_involved_in_agreement as uig', function ($join) {
                        $join->whereRaw('FIND_IN_SET(uig.id, mednotification.user_id)');
                    })
                    ->where("uig.userId", $userId)
                    ->where('view_user', "!=", 2)
                    ->orderBy('mednotification.id', 'DESC')->get();

        return $result;
    }

    public static function usernotificationbyctgry($userId, $category, $start, $length, $sortOrder)
    {

         $query = Notification::select('mednotification.*', 'ec.ititle', 'ec.idescription')
                    ->leftJoin('event_codes as ec', DB::raw('ec.code'), '=', DB::raw('mednotification.event'))
                    ->leftJoin('users as u', function ($join) {
                        $join->whereRaw('FIND_IN_SET(uig.id, mednotification.user_id)');
                    })
                    ->where("mednotification.userId", $userId)
                    ->where('view_user', "!=", 2);

                if (!empty($category)) {
                    $query->where('mednotification.category', $category);
                }

                if (!empty($sortOrder)) {
                    $query->orderBy('mednotification.id', $sortOrder);
                } else {
                    $query->orderBy('mednotification.id', 'DESC');
                }

        $result = $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
        return $result;
    }

}
