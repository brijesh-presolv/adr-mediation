<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RestructureData extends Model
{
    use HasFactory;
    protected $table = 'restructure_data';
    public $timestamps = false;

    protected $fillable = [
        'caseid',
        'res_user_id',
        'offer_id',
        'offer_name',
        'cl_user_id',
        'restructureFile'
    ];

}