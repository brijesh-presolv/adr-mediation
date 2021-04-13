<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use App\Models\MedCase;

use Auth;

use Validator;

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

       $InvoledUser=InvoledUser::where(['userPlanId'=>$med->id])->get()->toArray();




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


            // add initiating party

            $inv=new InvoledUser();
            $inv->userId=Auth::user()->id;
            $inv->userPlanId=$med->id;
            $inv->userEmail=Auth::user()->email;
            $inv->userPhone=Auth::user()->mobile_number;
            $inv->name=Auth::user()->first_name.' '.Auth::user()->last_name;
            $inv->address1=Auth::user()->address;
            $inv->address2=Auth::user()->address1;
            $inv->city=Auth::user()->city;
            $inv->pincode=Auth::user()->pincode;
            $inv->state=Auth::user()->state;
            $inv->country=Auth::user()->country;
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
            $inv->isClaimant=$i;



            $inv->created_at=date('Y-m-d H:s:i');
            $inv->updated_at=date('Y-m-d H:s:i');


            $inv->save();

            }


            exit();
        }

    	return view('user.invoke',['user'=>Auth::user(),'InvoledUser'=>$InvoledUser,'medcase'=>$med]);
    }


     public function newcase(Request $request){


           if($request->post()){
            
            $r=$request->post();

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


    public function newrequest(){


        $new=InvoledUser::select('user_involved_in_agreement.*','mediation_case.id as caseid')->where(['user_involved_in_agreement.userid'=>Auth::user()->id])->leftJoin('mediation_case', 'user_involved_in_agreement.userPlanId', '=', 'mediation_case.id')->get();



        return view('user.newrequest',['pending'=>$new]);
    }
}


