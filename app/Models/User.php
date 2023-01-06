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
}
