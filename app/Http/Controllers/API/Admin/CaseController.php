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
use App\Models\Mediators_mediation_cases_status;


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
                $data[$key]['caseid'] ='M' . sprintf('%06d', $values->id);
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


    public function mediatorList() {
        $users = User::select('id as mediator_id', DB::raw("CONCAT(users.first_name,' ',users.last_name, ' - ', users.organization) as mediator_name"),)->where("role", "=", 1)->get();
        $result['success'] = true;
        $result['message'] = "Mediators fetched successfully.";
        $result['data'] = $users;
        return response()->json($result, 200);
    }


    public function mediatorAssign(Request $request) {
        if ($request->input('mediator_id') != null) {
            $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                ->where(['user_involved_in_agreement.userPlanid' => $request->input('caseid')])->get();

            $mid = "M" . sprintf("%06d", $request->input('caseid'));




            if ($inv[0]->address1 != null || $inv[0]->useraddress != null) {
                $medcase = MedCase::find($request->input('caseid'));

                $data = Mediators_mediation_cases_status::where("mediation_case_id", "=", $request->input('caseid'))
                    ->where(function ($q) {
                        $q->where("status", "=", 0)
                            ->orWhere("status", "=", 1);
                    })
                    ->count();
                if ($data == 0) {
                    Mediators_mediation_cases_status::create([
                        'mediator_id' => $request->input('mediator_id'),
                        'mediation_case_id' => $request->input('caseid'),
                        'status' => 0,
                        'user_type' => 1,
                    ]);
                } else {
                    $MedCaseStatus = Mediators_mediation_cases_status::where(function ($q) {
                        $q->where("status", "=", 0)
                            ->orWhere("status", "=", 1);
                    })
                        ->where("mediation_case_id", "=", $request->input('caseid'))
                        ->first();
                    $MedCaseStatus->mediator_id = $request->input('mediator_id');
                    $MedCaseStatus->status = 0;
                    $MedCaseStatus->save();
                }

                //generate pdf

                /*
                
                $invitation = $this->mediator_appointment($request->id, $request->midater);
                
                
                $invmodel = InvitationFiles::where('case_id', $request->id)->orderByDesc('id')->limit(1)->first();

                if (!isset($invmodel)) {
                    $invmodel = new InvitationFiles();
                }
                $invmodel->case_id = $request->id;
                $invmodel->file_name_mediator_appointment = $invitation;
                $invmodel->save();

                if ($medcase->bulk_flag == 0 && $medcase->stop_itm_med == 0) {
                    $this->send_mediatorAdd($request->id, $request->midater);
                }

                */

                $finaldata['mediator_id'] = $request->input('mediator_id');
                $result['success'] = true;
                $result['message'] = "Mediators assigned successfully.";
                


                // confirm status for case

                $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $request->input('caseid'))
                    
                    ->first();
                    $inv_id = "";
                   
                    $inv = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
                        ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
                        ->where(['user_involved_in_agreement.userPlanid' => $request->input('caseid')])->get();
                    foreach ($inv as $v) {
                        if ($inv_id == "") {
                            $inv_id = $v->id;
                        } else {
                            $inv_id = $inv_id . "," . $v->id;
                        }
                    }

                    $medCas = MedCase::find($request->input('caseid'));
                    $medCas->confirm_status = 1;
                    $medCas->case_status = 1;
                    /*** Discussion field : START ***/
                    $medCas->discussion = $request->input('discussion_text');
                    /*** Discussion field : END ***/
                    $medCas->save();

                    $mediation_status_log = new Mediation_status_log;
                    $mediation_status_log->user_id = $request->input('userid');
                    $mediation_status_log->mediation_case_id = $request->input('caseid');
                    $mediation_status_log->status = 1;
                    $mediation_status_log->description = "Request Confirm";
                    $mediation_status_log->save();

                    // $reminder = new Reminder;
                    // $reminder->case_Id = $request->id;
                    // $reminder->save();

                    $finaldata['discussion'] = $request->input('discussion_text');
                    $finaldata['caseid'] = $request->input('caseid');
                    $result['data'] = $finaldata;
                    return response()->json($result, 200);

            }
        }
    }

}
