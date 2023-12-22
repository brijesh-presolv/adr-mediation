<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettlementPayment extends Model
{
    use HasFactory;
    protected $table = 'settlement_payments';

    protected $fillable = [
        'caseid',
        'userid',
        'actual_amt',
        'discount_amt',
        'total_amt',
        'payToken',
        'payment_req_token',
        'payment_request_id',
        'payment_security_token',
        'payment_id',
        'pay_status',
        'payment_req_created',
        'success_at',
        'Payfilename',
    ];


    static function getrespondent($caseid)
    {
        $sql = MedCase::with('user_involed');
        $result= $sql->select('user_involved_in_agreement.*')
                    ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
                    ->where('user_involved_in_agreement.userPlanId', $caseid)
                    ->where('user_involved_in_agreement.isClaimant', 1)
                    ->first();

        return $result;
    }
}