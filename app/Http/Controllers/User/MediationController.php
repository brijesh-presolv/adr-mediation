<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvoledUser;
use Auth;

class MediationController extends Controller
{
    

    public function invoke(Request $request){


        if($request->post()){


            
            $r=$request->post();

            for ($i=0; $i < count($r['email']); $i++) { 
              

            $inv=new InvoledUser();
            $inv->userId=Auth::user()->id;
            $inv->userPlanId=1;
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

    	return view('user.invoke');
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
}


