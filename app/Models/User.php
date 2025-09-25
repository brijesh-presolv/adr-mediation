<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'mobile_number',
        'organization',
        'email',
        'role',
        'password',
        'isDone',
        'status',
        'emailotp',
        'smsotp',
        'isActive',
        'signature_photo',
        'is_deleted',
        'is_agree'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'isActive' => 'boolean',
    ];

    // Get User Data : START //
    static function getUserdetails($userid) {
        
        $query = "SELECT id,role,email,last_name,organization,mobile_number,address,address1,city,pincode,state,country,isActive,created_at,updated_at,email_verified_at,smsotp,username,profile_pic from users where id = ".$userid;
        $details = DB::select($query);
        return $details[0];
    }
    // Get User Data : END //

    static function getUserApprove($role, $start, $length, $search, $columnName, $sortOrder)
    {

        $query = User::select('*')->where("role", "=", $role)
                    ->where('status', 1)
                    ->where('is_deleted', 0);

                if (!empty($search)) {

                    $query->where(function ($q) use ($search) {
                        $q->where(DB::raw('concat(first_name," ",last_name)'), 'LIKE', "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("mobile_number", "like", "%{$search}%");
                    });
                }

                if ($columnName == "id") {
                    $query->orderBy('id', $sortOrder);
                } elseif ($columnName == "date") {
                    $query->orderBy('created_at', $sortOrder);
                } elseif ($columnName == "full_name") {
                    $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
                }elseif ($columnName == "full_name") {
                     $quer->orderBy('email', $sortOrder);
                }elseif ($columnName == "full_name") {
                     $query->orderBy('mobile_number', $sortOrder);
                }
                 else {

                    $query->orderBy('id', 'DESC');
                }

        return $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
    }

    static function getUserNewReq($role, $start, $length, $search, $columnName, $sortOrder)
    {

        $query = User::select('*')->where("role", "=", $role)
                    ->where('status', 0)
                    ->where('is_deleted', 0);

                if (!empty($search)) {

                    $query->where(function ($q) use ($search) {
                        $q->where(DB::raw('concat(first_name," ",last_name)'), 'LIKE', "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("mobile_number", "like", "%{$search}%");
                    });
                }

                if ($columnName == "id") {
                    $query->orderBy('id', $sortOrder);
                } elseif ($columnName == "date") {
                    $query->orderBy('created_at', $sortOrder);
                } elseif ($columnName == "full_name") {
                    $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
                }elseif ($columnName == "full_name") {
                     $quer->orderBy('email', $sortOrder);
                }elseif ($columnName == "full_name") {
                     $query->orderBy('mobile_number', $sortOrder);
                }
                 else {

                    $query->orderBy('id', 'DESC');
                }

        return $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
    }

    static function getUserRejected($role, $start, $length, $search, $columnName, $sortOrder)
    {

        $query = User::select('*')->where("role", "=", $role)
                    ->where('is_deleted', 1);

                if (!empty($search)) {

                    $query->where(function ($q) use ($search) {
                        $q->where(DB::raw('concat(first_name," ",last_name)'), 'LIKE', "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("mobile_number", "like", "%{$search}%");
                    });
                }

                if ($columnName == "id") {
                    $query->orderBy('id', $sortOrder);
                } elseif ($columnName == "date") {
                    $query->orderBy('created_at', $sortOrder);
                } elseif ($columnName == "full_name") {
                    $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
                }elseif ($columnName == "full_name") {
                     $quer->orderBy('email', $sortOrder);
                }elseif ($columnName == "full_name") {
                     $query->orderBy('mobile_number', $sortOrder);
                }
                 else {

                    $query->orderBy('id', 'DESC');
                }

        return $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
    }

    static function getMediatorsApprove($role, $start, $length, $search, $columnName, $sortOrder)
    {

        $query = User::select('users.*', 'mediation_details.user_id', 'mediation_details.area_of_specialization', 'mediation_details.no_of_arbitrations', 'mediation_details.linked_in_profile_link', 'mediation_details.experience', 'mediation_details.is_accept1', 'mediation_details.is_accept2', 'mediation_details.is_accept3', 'mediation_details.filed1', 'mediation_details.filed2', 'mediation_details.filed3')
                    ->leftJoin("mediation_details", "mediation_details.user_id", "=", "users.id")
                    ->where("role", "=", $role)
                    ->where('status', 1)
                    ->where('is_deleted', 0);

                if (!empty($search)) {

                    $query->where(function ($q) use ($search) {
                        $q->where(DB::raw('concat(first_name," ",last_name)'), 'LIKE', "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("mobile_number", "like", "%{$search}%");
                    });
                }

                if ($columnName == "id") {
                    $query->orderBy('id', $sortOrder);
                } elseif ($columnName == "date") {
                    $query->orderBy('created_at', $sortOrder);
                } elseif ($columnName == "full_name") {
                    $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
                }elseif ($columnName == "full_name") {
                     $quer->orderBy('email', $sortOrder);
                }elseif ($columnName == "full_name") {
                     $query->orderBy('mobile_number', $sortOrder);
                }
                 else {

                    $query->orderBy('id', 'DESC');
                }

        return $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
    }

    static function getMediatorsNewReq($role, $start, $length, $search, $columnName, $sortOrder)
    {

        $query = User::select('users.*', 'mediation_details.user_id', 'mediation_details.area_of_specialization', 'mediation_details.no_of_arbitrations', 'mediation_details.linked_in_profile_link', 'mediation_details.experience', 'mediation_details.is_accept1', 'mediation_details.is_accept2', 'mediation_details.is_accept3', 'mediation_details.filed1', 'mediation_details.filed2', 'mediation_details.filed3')->leftJoin("mediation_details", "mediation_details.user_id", "=", "users.id")
                    ->where("role", "=", $role)
                    ->where('status', 0)
                    ->where('is_deleted', 0);

                if (!empty($search)) {

                    $query->where(function ($q) use ($search) {
                        $q->where(DB::raw('concat(first_name," ",last_name)'), 'LIKE', "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("mobile_number", "like", "%{$search}%");
                    });
                }

                if ($columnName == "id") {
                    $query->orderBy('id', $sortOrder);
                } elseif ($columnName == "date") {
                    $query->orderBy('created_at', $sortOrder);
                } elseif ($columnName == "full_name") {
                    $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
                }elseif ($columnName == "full_name") {
                     $quer->orderBy('email', $sortOrder);
                }elseif ($columnName == "full_name") {
                     $query->orderBy('mobile_number', $sortOrder);
                }
                 else {

                    $query->orderBy('id', 'DESC');
                }

        return $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
    }

    static function getMediatorsRejected($role, $start, $length, $search, $columnName, $sortOrder)
    {

        $query = User::select('users.*', 'mediation_details.user_id', 'mediation_details.area_of_specialization', 'mediation_details.no_of_arbitrations', 'mediation_details.linked_in_profile_link', 'mediation_details.experience', 'mediation_details.is_accept1', 'mediation_details.is_accept2', 'mediation_details.is_accept3', 'mediation_details.filed1', 'mediation_details.filed2', 'mediation_details.filed3')->leftJoin("mediation_details", "mediation_details.user_id", "=", "users.id")
        ->where("role", "=", $role)
                    ->where('is_deleted', 1);

                if (!empty($search)) {

                    $query->where(function ($q) use ($search) {
                        $q->where(DB::raw('concat(first_name," ",last_name)'), 'LIKE', "%{$search}%")
                        ->orWhere("email", "like", "%{$search}%")
                        ->orWhere("mobile_number", "like", "%{$search}%");
                    });
                }

                if ($columnName == "id") {
                    $query->orderBy('id', $sortOrder);
                } elseif ($columnName == "date") {
                    $query->orderBy('created_at', $sortOrder);
                } elseif ($columnName == "full_name") {
                    $query->orderByRaw("CONCAT(first_name, ' ', last_name) {$sortOrder}");
                }elseif ($columnName == "full_name") {
                     $quer->orderBy('email', $sortOrder);
                }elseif ($columnName == "full_name") {
                     $query->orderBy('mobile_number', $sortOrder);
                }
                 else {

                    $query->orderBy('id', 'DESC');
                }

        return $query->paginate($length, ['*'], 'page', floor($start / $length) + 1);
    }
}
