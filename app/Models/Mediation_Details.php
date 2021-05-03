<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mediation_Details extends Authenticatable {

    use HasFactory;

    protected $table = 'mediation_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_of_specialization',
        'no_of_arbitrations',
        'linked_in_profile_link',
        'experience',
        'is_accept1',
        'is_accept2',
        'is_accept3',
        'filed1',
        'filed2',
        'filed3',
        'filed3',
    ];

}
