<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvitationFiles;
use App\Models\InvoledUser;
use App\Models\MedCase;
use App\Models\Mediators_mediation_cases_status;
use App\Models\SupportingDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IvrController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

   
    public function acceptcase()
    {
        $date = \Carbon\Carbon::today()->subDays(1);

        $date = $date->format('Y-m-d');
       
        $cases = MedCase::select('mediation_case.*', 'mediators_mediation_cases_status.mediator_id', 'mediators_mediation_cases_status.status', 'mediators_mediation_cases_status.created_at as date')
            ->leftJoin('mediators_mediation_cases_status', DB::raw('mediators_mediation_cases_status.mediation_case_id'), '=', DB::raw('mediation_case.id'))
            ->whereDate('mediators_mediation_cases_status.created_at', $date)->get();
      
        $arraydata = array();
        foreach ($cases as $key => $case) {
           
                
                $party=InvoledUser::where(['userPlanid' => $case->id])->get();

                $claimant='';

                $respondent='';

                $contact="";

                $i=0;

                foreach ($party as $k => $v) {
                    
                    if($v->isClaimant=='0' and $i==0){


                        $claimant=User::where(['id'=>$v->userId])->first();

                        if($claimant->organization==''){

                            $claimant=$claimant->first_name.' '.$claimant->last_name;
                        } else{

                            $claimant=$claimant->organization;
                        }
                               

                    } else if($i==1 and $v->isClaimant=='1'){

                        $respondent=$v->name;

                        $contact=$v->userPhone;

                    }

                    $i++;

                }



        echo $template='Hello '.$respondent.' a legal case of arbitration has been registered on Presolv three sixty platform against you by '.$claimant;

        echo $auth="MED360AUTH";

        echo $event="ACPTMED_ADM";

        echo $app="P360MED";

        echo $pivrid="61f7db108353a316";

        echo $caseid=$case->id;


       exit();


               
            
        }

        




        return response()->json(["code" => 200, "status" => "success", "data" => $arraydata]);
    }

}
