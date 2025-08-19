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

        // $draw = $_POST['sEcho'];
        // $row = $_POST['iDisplayStart'];
        // $rowperpage = $_POST['iDisplayLength']; // Rows display per page
        // $indexColumn = $_POST['iSortCol_0'];
        // $columnName = $_POST['mDataProp_' . $indexColumn]; // Column name
        // $columnSortOrder = $_POST['sSortDir_0']; // asc or desc
        // $batch_id = "";
        // if (isset($_POST['batch_id'])) {
        //     $batch_id = $_POST['batch_id'];
        // }
        // $searchValue = $_POST['sSearch'];

        $role = 2; // admin
        $bulk = 0;

       // $casescount = MedCase::getCaseCount($searchValue, $role, $batch_id, $bulk);
       // $cases = MedCase::getCase($searchValue, $columnName, $columnSortOrder, $draw, $row, $rowperpage, $role, $batch_id, $bulk);
        $cases = MedCase::getCase($role, $bulk);

        //dd($cases);

        $arraydata = array();
        foreach ($cases as $key => $d) {
            $actionDate = date('d-m-Y', strtotime($d->update));
            $createDate = date('d-m-Y', strtotime($d->create));
            $admin_approve = date('d-m-Y', strtotime($d->admin_approve));

            
            if($d->sub_user_id != null){
                $sub_user_data = User::select("first_name", "last_name")->where("id", $d->sub_user_id)->first();
                $sub_user = $sub_user_data->first_name ." ".$sub_user_data->last_name;
            } else {
                $sub_user = "";
            }  
            
            $arraydata[] = [
                "key" => $key + 1,
                "date" => date('d-m-Y', strtotime($d->created_at)),
                "mediator_action_date" => $actionDate,
                "mediator_create_action_date" => $createDate,
                "admin_approve" => $admin_approve,
                "case" => $d,
                "party" => InvoledUser::select('user_involved_in_agreement.id', 'user_involved_in_agreement.name', 'user_involved_in_agreement.isOnboarded', 'user_involved_in_agreement.isClaimant', "user_involved_in_agreement.userId", "users.organization")->leftjoin('users', 'users.id', '=', 'user_involved_in_agreement.userId')->where(['userPlanid' => $d->id])->get(),
                "status_log" => Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $d->id])->orderByDesc('id')->limit(1)->get(),
                "private_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->count(),
                "private_view_count" => Mediation_case_comment::where("type", "=", 1)->where('mediation_case_id', $d->id)->where('view', 0)->count(),
                "share_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->count(),
                "share_view_count" => Mediation_case_comment::where("type", "=", 0)->where('mediation_case_id', $d->id)->where('view', 0)->count(),
                "sub_user" => $sub_user
            ];
        }

        return response()->json($arraydata, 500);
    }

}
