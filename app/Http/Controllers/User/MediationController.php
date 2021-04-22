<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use App\Models\User;

use App\Models\MedCase;
use Session;
use Auth;

use Validator;
use DB;

class MediationController extends Controller
{
    

    public function invoke(Request $request){



       if(!isset($_GET['id'])){

          return abort(404);
       } else if(isset($_GET['id'])){

           $med=MedCase::find($_GET['id']);


           if(!$med){

             return abort(404);
           }

       }


       //fetch all involved users

       $InvoledUser=InvoledUser::where(['userPlanId'=>$med->id])->where('isClaimant','<>','0')->get()->toArray();




        if($request->method()=='POST' && $InvoledUser==null){





            //upload file 
            $filename='';
            if($request->file('document')!==null){

                $request->validate([
                 'document' => 'mimes:pdf|max:20048',
         
                ]);

                $filename='supporting_document'.$med->id.time().'.'.$request->document->extension();

         
                $path = $request->file('document')->storeAs('public/mediation/'.$med->id.'/',$filename);

            }


            $r=$request->post();



            //udpate mediation case

            $med->issue=$r['issue'];
            $med->documentPath=$filename;
            $med->updated_at=date("Y-m-d H:i:s");
            $med->save();

            //if user profile update

            $usr=User::find(Auth::user()->id);

            if(Auth::user()->address=='NULL'){
                    $usr->address=$r['useraddress'];
                    $usr->address1=$r['useraddress1'];
                    $usr->city=$r['usercity'];
                    $usr->pincode=$r['userpincode'];
                    $usr->state=$r['userstate'];
                    $usr->country=$r['usercountry'];
                    $usr->save();



            }


            // add initiating party

            $inv=new InvoledUser();
            $inv->userId=$usr->id;
            $inv->userPlanId=$med->id;
            $inv->userEmail=$usr->email;
            $inv->userPhone=$usr->mobile_number;
            $inv->name=$usr->first_name.' '.$usr->last_name;
            $inv->address1=$usr->address;
            $inv->address2=$usr->address1;
            $inv->city=$usr->city;
            $inv->pincode=$usr->pincode;
            $inv->state=$usr->state;
            $inv->country=$usr->country;
            $inv->isClaimant='0';
            $inv->isOnboarded='1';




            $inv->created_at=date('Y-m-d H:s:i');
            $inv->updated_at=date('Y-m-d H:s:i');


            $inv->save();


            //add responding party

            
            

            for ($i=0; $i < count($r['email']); $i++) { 
              

            $inv=new InvoledUser();
            //$inv->userId=;
            $inv->userPlanId=$med->id;
            $inv->userEmail=$r['email'][$i];
            $inv->userPhone=$r['phone'][$i];
            $inv->name=$r['name'][$i];
            $inv->joinCode=$this->joinCode();
            $inv->address1=$r['add1'][$i];
            $inv->address2=$r['add2'][$i];
            $inv->city=$r['city'][$i];
            $inv->pincode=$r['pincode'][$i];
            $inv->state=$r['state'][$i];
            $inv->country=$r['country'][$i];
            $inv->isClaimant=$i+1;



            $inv->created_at=date('Y-m-d H:s:i');
            $inv->updated_at=date('Y-m-d H:s:i');


            $inv->save();

            }

            return redirect()->route('user.newrequest')->with(['response'=>'success']);

            exit();
        }

    	return view('user.invoke',['user'=>Auth::user(),'InvoledUser'=>$InvoledUser,'medcase'=>$med]);
    }


     public function newcase(Request $request){


            if($request->session()->has('newcase')){
            
            $r=$request->session()->get('newcase');

            $med=new MedCase();

            $med->userid=Auth::user()->id;

            $med->disputeCategory=$r['cat'];

            $med->noOfParties=$r['npd'];

            $med->amount=$r['damount'];

            $med->confirm_status=0;

            $med->created_at=date('Y-m-d H:i:s');

            $med->updated_at=date('Y-m-d H:i:s');

            if($med->save()){

                    return redirect()->route('user.invoke','id='.$med->id);

            }

        }
    }




    public function joinCode(){

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;
    }


    public function newrequest(Request $request){



       // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        $new=MedCase::select('user_involved_in_agreement.*','mediation_case.id as caseid','mediation_case.created_at as date')->where(['mediation_case.userid'=>Auth::user()->id,'mediation_case.confirm_status'=>0])->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')->orderby('mediation_case.id')->get();


        return view('user.newrequest',['pending'=>$new,'response'=>Session::get('response')]);
    }

     public function ongoing(){


       // $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();


        $new=MedCase::select('user_involved_in_agreement.*','mediation_case.id as caseid','mediation_case.created_at as date',DB::raw('concat(users.first_name) as mediator'))
        ->where(['mediation_case.userid'=>Auth::user()->id,'mediation_case.confirm_status'=>1])
        ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        ->leftJoin('user_involved_in_agreement', 'mediation_case.id', '=', 'user_involved_in_agreement.userPlanId')

        ->get();

        return view('user.ongoing',['ongoing'=>$new]);
    }

    public function sessions(Request $request) {


        $sessionData = DB::table('manage_session')->where('case_id', $request->caseid)->get();
        $sn = 1;
        foreach ($sessionData as $value) {
            echo "<tr>";
            echo "<td>" . $sn . "</td>";
            echo "<td>" . $value->created_at . "</td>";
            echo "<td>" . $value->session_date . "</td>";
            echo "<td>" . $value->zoom_id . "</td>";
            echo "<td>" . $value->note . "</td>";
            echo "</tr>";

            $sn++;
        }
        // return $sessionData;
    }

    public function casedetails($id){



        $case= MedCase::select("mediation_case.*", "users.username as mediator", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status")
                ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
                ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where('mediation_case.id','=',$id)
                ->first();

        $case->party=InvoledUser::where(['userPlanid' => $case->id])->get();

        
       return view('user.casedetails',compact("case"));
    }
}


