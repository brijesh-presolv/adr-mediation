<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reminder extends Model {

    use HasFactory;

    protected $table = 'remainder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_Id',
        'inv_user_id',
        'send_reminder'
    ];

    

}
