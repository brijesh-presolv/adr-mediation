<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\ManageSession;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;

class CaseController extends Controller 
{

    public function newreq(Request $request){

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('sSearch', '');
        //$batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;

        $cases = MedCase::getCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder);

        //dd($cases);

       // $arraydata = array();
        $data = array();
        if(count($cases) > 0) {
        foreach ($cases as $key => $values) {
            
                $id = $values->id;
                $keyInc = $key + 1;
                $data[$key]['id'] = $id;
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;

                // Claimants
                $claimants = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                        ->where('user_involved_in_agreement.isClaimant', 1)
                                        ->where('userPlanid', $id)
                                        ->get();
                // Respondents
                $respondents = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                            ->where('user_involved_in_agreement.isClaimant', 0)
                                            ->where('userPlanid', $id)
                                            ->get();
                $data[$key]['claimants']  = $claimants;
                $data[$key]['respondents'] = $respondents;
        }
        }

        $casedata['cases']=$data;
        $casedata['pagination']['total_count']=$cases->total();
        $casedata['pagination']['current_page']=$cases->currentPage();
        $casedata['pagination']['per_page']=$cases->perPage();
        $casedata['pagination']['total_page']=$cases->lastPage();

        $result['success'] = true;
        $result['message'] = "New cases fetched successfully.";
        $result['data'] = $casedata;
        return response()->json($result, 200);

    }


    public function ongoing(Request $request){

        $start   = $request->input('iDisplayStart', 0);    // offset
        $length  = $request->input('iDisplayLength', 10);  // limit
        $search  = $request->input('sSearch', '');
        $batch_id = $request->input('batch_id', null);
        $sortOrder = $request->input('SortOrder', 'desc'); // asc or desc
        $columnName = $request->input('columnName', ''); 
        

        $role = 2; // admin
        $bulk = 0;
        $casesongoing = MedCase::getOgoingCaseApi($role, $bulk, $start, $length, $search, $columnName, $sortOrder , $batch_id);
        $data = array();
        if(count($casesongoing) > 0) {

            foreach ($casesongoing as $key => $values) {

                $id = $values->id;
                $keyInc = $key + 1;
                $data[$key]['id'] = $id;
                $data[$key]['keyInc'] = $keyInc;
                $data[$key]['batch_id'] = $values->batch_id;
                $data[$key]['ref_id'] = $values->ref_id;
                $data[$key]['confirm_status'] = $values->confirm_status;
                $data[$key]['case_status'] = $values->case_status;
                $data[$key]['created_at'] = $values->created_at;
                $data[$key]['mediator_name'] = $values->mediator_name;
                $data[$key]['mediator_id'] = $values->mediator_id;
                $data[$key]['mediator_status'] = $values->mediator_status;
                $data[$key]['batch_name'] = $values->batch_name;

            // Claimants
            $claimants = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                    ->where('user_involved_in_agreement.isClaimant', 1)
                                    ->where('userPlanid', $id)
                                    ->get();
            // Respondents
            $respondents = InvoledUser::select('user_involved_in_agreement.name', 'user_involved_in_agreement.userEmail as email')
                                        ->where('user_involved_in_agreement.isClaimant', 0)
                                        ->where('userPlanid', $id)
                                        ->get();
            $data[$key]['claimants']  = $claimants;
            $data[$key]['respondents'] = $respondents;

            }
        }



        $casedata['cases']=$data;
        $casedata['pagination']['total_count']=$casesongoing->total();
        $casedata['pagination']['current_page']=$casesongoing->currentPage();
        $casedata['pagination']['per_page']=$casesongoing->perPage();
        $casedata['pagination']['total_page']=$casesongoing->lastPage();

        $result['success'] = true;
        $result['message'] = "Ongoing cases fetched successfully.";
        $result['data'] = $casedata;
        return response()->json($result, 200);
    }

}
