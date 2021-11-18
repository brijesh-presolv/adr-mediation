<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedCase extends Model
{
    use HasFactory;

    protected $table = 'mediation_case';
    protected $fillable = ['userid', 'disputeCategory', 'noOfParties', 'amount', 'issue', 'confirm_status', 'documentPath', 'withdraw', 'otherRespondentDetails'];

    static function getcasebyId($id)
    {


        // foreach ($details as $data) {

        //     $result = $data->to_array();
        // }

        //  return $result;
    }

}
