<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedCase;
use App\Models\Mediation_status_log;
use App\Models\Mediation_case_comment;
use App\Models\InvoledUser;
use App\Models\SupportingDocument;
use App\Models\User;
use App\Models\InvitationFiles;

use App\Models\Mediators_mediation_cases_status;
use Session;
use Auth;
use Validator;
use DB;

class MediationController extends Controller {

    public function invoke(Request $request) {



        if (!isset($_GET['id'])) {

            return abort(404);
        } else if (isset($_GET['id'])) {

            $med = MedCase::find($_GET['id']);


            if (!$med) {

                return abort(404);
            }
        }


        //fetch all involved users

        $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->where('isClaimant', '<>', '0')->get()->toArray();




        if ($request->method() == 'POST' && $InvoledUser == null) {





            //upload file 
            $filename = '';
            if ($request->file('document') !== null) {

                $request->validate([
                    'document' => 'mimes:pdf,zip,jpg,jpeg,png|max:20048',
                ]);

                $filename = 'supporting_document' . $med->id . time() . '.' . $request->document->extension();


                $path = $request->file('document')->storeAs('public/mediation/' . $med->id . '/', $filename);
            }


            $r = $request->post();



            //udpate mediation case

            $med->issue = $r['issue'];
            $med->documentPath = $filename;
            $med->updated_at = date("Y-m-d H:i:s");
            $med->save();

            //if user profile update

            $usr = User::find(Auth::user()->id);

            if (Auth::user()->address == '') {
                $usr->address = $r['useraddress'];
                $usr->address1 = $r['useraddress1'];
                $usr->city = $r['usercity'];
                $usr->pincode = $r['userpincode'];
                $usr->state = $r['userstate'];
                $usr->country = $r['usercountry'];
                $usr->save();
            }


            // add initiating party



            $inv=InvoledUser::where(['userPlanid'=>$med->id,'userId'=>$usr->id])->first();

            if(!$inv){

            $inv = new InvoledUser();
            $inv->userId = $usr->id;
            $inv->userPlanId = $med->id;
            $inv->userEmail = $usr->email;
            $inv->userPhone = $usr->mobile_number;
            $inv->name = $usr->first_name . ' ' . $usr->last_name;
            $inv->address1 = $usr->address;

            if($usr->address1==''){
                $usr->address1='';
            }
            $inv->address2 = $usr->address1;
            $inv->city = $usr->city;
            $inv->pincode = $usr->pincode;
            $inv->state = $usr->state;
            $inv->country = $usr->country;
            $inv->isClaimant = '0';
            $inv->isOnboarded = '1';




            $inv->created_at = date('Y-m-d H:s:i');
            $inv->updated_at = date('Y-m-d H:s:i');


            $inv->save();

        }


            //add responding party




            for ($i = 0; $i < count($r['email']); $i++) {


                $inv = new InvoledUser();
                //$inv->userId=;
                $inv->userPlanId = $med->id;
                $inv->userEmail = $r['email'][$i];
                $inv->userPhone = $r['phone'][$i];
                $inv->name = $r['name'][$i];
                $inv->joinCode = $this->joinCode();
                $inv->address1 = $r['add1'][$i];

                if($r['add2'][$i]==''){
                    $r['add2'][$i]='';
                }
                $inv->address2 = $r['add2'][$i];
                $inv->city = $r['city'][$i];
                $inv->pincode = $r['pincode'][$i];
                $inv->state = $r['state'][$i];
                $inv->country = $r['country'][$i];
                $inv->isClaimant = $i + 1;



                $inv->created_at = date('Y-m-d H:s:i');
                $inv->updated_at = date('Y-m-d H:s:i');


                $inv->save();
            }

                // l1 to submit user

                $email = new \SendGrid\Mail\Mail();
                $email->setFrom("no-repley@mediatation.livetest.top", "No Repley");
                $email->setSubject('Thank you for choosing Mediation');
                $email->addTo($usr->email, $usr->first_name.' '.$usr->last_name);
                $html = view('email.l1');
                //dd;
                $email->addContent("text/html", $html->render());
                //echo env('SENDGRID_API_KEY', 'test');
                $sendgrid = new \SendGrid(env('SENDGRID_API_KEY', 'Laravel'));
                try {
                    $response = $sendgrid->send($email);

                    //$response->statusCode() . "\n";
                    //print_r($response->headers());
                    //return $response->body() . "\n";
                } catch (Exception $e) {
                    echo 'Caught exception: ' . $e->getMessage() . "\n";

                }
                

            return redirect()->route('user.newrequest')->with(['response' => 'success']);

            exit();
        }

        return view('user.invoke', ['user' => Auth::user(), 'InvoledUser' => $InvoledUser, 'medcase' => $med]);
    }

    public function newcase(Request $request) {


        if ($request->session()->has('newcase')) {

            $r = $request->session()->get('newcase');

            $med = new MedCase();

            $med->userid = Auth::user()->id;

            $med->disputeCategory = $r['cat'];

            $med->noOfParties = $r['npd'];

            $med->amount = $r['damount'];

            $med->confirm_status = 0;

            $med->created_at = date('Y-m-d H:i:s');

            $med->updated_at = date('Y-m-d H:i:s');

            if ($med->save()) {

                $request->session()->forget('newcase');

                return redirect()->route('user.invoke', 'id=' . $med->id);
            }
        }
    }

    public function joinCode() {

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;


    }

    public function join(Request $request) {



        if ($request->post()) {

            $r = $request->post();

            $code = $r['joincode'];

            $email = Auth::user()->email;


            $InvoledUser = InvoledUser::where(['joincode' => $code, 'userEmail' => $email])->first();


            if (!$InvoledUser) {

                return response()->json(['response' => 'Invalid']);
            }

            $case = MedCase::where(['id' => $InvoledUser->userPlanId, 'confirm_status' => 1])->first();

            if (!$case) {

                return response()->json(['response' => 'Invalid']);
            }

            $InvoledUser->joincode = null;
            $InvoledUser->isOnboarded='1';
            $InvoledUser->userid = Auth::user()->id;

            if ($InvoledUser->save()) {


                //fetch init parry
                $id = "M" . sprintf("%06d", $InvoledUser->userPlanId);
                
                $InvoledUserP1 = InvoledUser::where(['isClaimant' => '0', 'userPlanId' => $InvoledUser->userPlanId])->first();

                

                $party_name=$InvoledUser->name;




                $email = new \SendGrid\Mail\Mail();
                $email->setFrom("no-repley@mediatation.livetest.top", "No Repley");
                $email->setSubject('Update about your case');
                $email->addTo($InvoledUserP1->userEmail, $InvoledUserP1->name);
                $html = view('email.l7_upon_successful_onboarding_of_any_counter_party',compact("id","party_name"));
    
                //dd;
                $email->addContent("text/html", $html->render());
                //echo env('SENDGRID_API_KEY', 'test');
                $sendgrid = new \SendGrid(env('SENDGRID_API_KEY', 'Laravel'));
                try {
                    $response = $sendgrid->send($email);
                    //$response->statusCode() . "\n";
                    //print_r($response->headers());
                    //return $response->body() . "\n";
                } catch (Exception $e) {
                    //echo 'Caught exception: ' . $e->getMessage() . "\n";
                }





                return response()->json(['response' => 'success', 'code' => 201]);
            }
        } else {

            return response()->json(['response' => 'error', 'code' => 404]);
        }
    }


    public function newrequest(Request $request) {



        // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        $new = MedCase::Where(['userid' => Auth::user()->id, 'confirm_status' => 0])->orderby('id','DESC')->get();

        $pending = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->id])->get();
            $value->party = $in;

            $pending[] = $value;
        }


        return view('user.newrequest', ['pending' => $pending, 'response' => Session::get('response')]);
    }

    public function ongoing() {


        // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date','mediation_case.userid',DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"))
                ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 1])
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
                ->orderby('mediation_case.id','DESC')
                ->get();

        $ongoing = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $value->party = $in;

            $value->casestatus=Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

            $ongoing[] = $value;
        }

        return view('user.ongoing', ['ongoing' => $ongoing]);
    }

    public function closed() {

        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date', DB::raw("CONCAT(users.first_name,' ',users.last_name,' - ',users.organization) as mediator"),'mediation_case.withdraw')
                ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 2])
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
                ->orderby('mediation_case.id','DESC')
                ->get();

        $closed = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $value->party = $in;

            $value->casestatus=Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

            $value->casestatus->css='';

            if($value->casestatus->status==2){

                $value->casestatus->css='danger';
            } else if($value->casestatus->status==5){

                $value->casestatus->css='danger';
            }else if($value->casestatus->status==6){

                $value->casestatus->css='success';
            }else if($value->casestatus->status==7){

                $value->casestatus->css='danger';
            }

            $closed[] = $value;
        }

        return view('user.closed', ['closed' => $closed]);
    }

    public function rejected() {

        $new = MedCase::select('user_involved_in_agreement.*', 'mediation_case.id as caseid', 'mediation_case.created_at as date','mediation_case.withdraw')
                ->where(['user_involved_in_agreement.userid' => Auth::user()->id, 'mediation_case.confirm_status' => 3])
                ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')
                ->get();

        $closed = [];

        foreach ($new as $key => $value) {
            $in = InvoledUser::select('name', 'isOnboarded')->where(['userPlanid' => $value->caseid])->get();
            $value->party = $in;

            $value->casestatus=Mediation_status_log::select("status", "description", DB::raw("DATE_FORMAT(created_at,'%d-%c-%y %h:%i %p') as created"))->where(['mediation_case_id' => $value->caseid])->orderByDesc('id')->limit(1)->first();

            $value->casestatus->css='danger';


            $closed[] = $value;
        }

        return view('user.rejected', ['closed' => $closed]);
    }

    public function sessions(Request $request) {


        $sessionData = DB::table('manage_session')->where('case_id', $request->caseid)->get();
        $sn = 1;
        $dataArray = array();

        foreach ($sessionData as $value) {
            if (!is_null($value->session_party_ids)) {
                $dataArray = json_decode($value->session_party_ids);
            }
            $user = array();
            foreach ($dataArray as $d) {
                $dd = User::find($d);
                $user[] = $dd->first_name . " " . $dd->last_name;
            }
            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td>" . $value->created_at . "</td>";
            echo "<td>" . $value->session_date . "</td>";
            echo "<td>" . $value->zoom_id . "</td>";
            echo "<td>" . $value->note . "</td>";
            echo "<td>" . implode("<br>", $user) . "</td>";
            echo "</tr>";

            $sn++;
        }
        // return $sessionData;
    }

    public function casedetails($id){



        $case= MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname","mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where('mediation_case.id', '=', $id)
                ->first();
                

        $case->party = InvoledUser::where(['userPlanid' => $case->id])->get();

        $case->invitation= InvitationFiles::where(['case_id'=>$case->id])->orderByDesc('id')->limit(1)->first();


        $case->supporting_document=SupportingDocument::where(['case_id' => $case->id])->get();

        return view('user.casedetails', compact("case"));
    }

    public function withdraw(Request $request) {



        $user = MedCase::find($request->case_id);
        $user->confirm_status = 2;
        $user->withdraw = $request->withdraw_comment;
        $user->save();

        $mediation_status_log = new Mediation_status_log;
        $mediation_status_log->user_id = Auth::user()->id;
        $mediation_status_log->mediation_case_id = $request->case_id;
        $mediation_status_log->status = 5;
        $mediation_status_log->description = "Request Withdrawn";
        $mediation_status_log->save();

        return response()->json(["msg" => "Case withdrawn"]);
    }

     /**
     * get added session data to view on ongoing.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function viewSettelment(Request $request) {

        $sessionData = DB::table('document_settlements')
                ->join('users', 'users.id', '=', 'document_settlements.uploaded_by')
                ->where('document_settlements.mediation_case_id', $request->id)
                ->get();
        $sn = 1;
        foreach ($sessionData as $value) {

            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td><a href='" . url("storage/app/" . $value->file_path) . "' target='_blank'>" . pathinfo($value->file_path, PATHINFO_FILENAME) . "</td>";
            echo "<td>" . $value->first_name . ' '.$value->last_name. "</td>";
            echo "</tr>";

            $sn++;
        }
        //return;
    }

}
