<?php

namespace App\Http\Controllers\API\User;

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
use App\Http\Helpers\Common_function;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\InvoledUser;
use App\Models\InvitationFiles;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\BulkLog;
use App\Models\EmailTrack;
use App\Models\Notification;
use App\Models\SendWhatsappChoice;
use App\Models\ConsentDisclosures;
use App\Http\Helpers\SendGrid;
use App\Http\Traits\UploadTrait;
use App\Rules\MatchOldPassword;
use App\Http\Helpers\SendGrid as Email;
use App\Models\Mediators_mediation_cases_status;

use PDF;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Http\Helpers\Zoom;


class MediationController extends Controller 
{
    use UploadTrait;

    // Case register api
    public function newCase(Request $request) {

            // Inputs
            $userId = 50;
            $category = $request->input('category');
            $amount = $request->input('amount');
            $issue = $request->input('issue');
            $proposedsolution = $request->input('proposedsolution');
            $application = $request->input('application');
            
            $inputData['claimants']= $request->input('claimants.*');
            $inputData['respondants']= $request->input('respondants.*');

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'amount' => 'required',
                'issue' => 'string',
                'proposedsolution' => 'string',
                'claimants.*.email' => 'unique',
                'respondants.*.email' => 'unique'
            ]);


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }



            $med = new MedCase();

            $med->userid = $userId;

            $med->disputeCategory = $category;
            $med->noOfParties = 1;
            $med->amount = $amount;
            $med->ref_id = $application;
            $med->issue = $issue;
            $med->proposedSolution = $proposedsolution;
            $med->confirm_status = 0;
            $med->bulk_flag = 0;
            $med->ref_id = $application;

            $med->created_at = date('Y-m-d H:i:s');

            $med->updated_at = date('Y-m-d H:i:s');

            $med->save();


            $usr = User::find($userId);


            foreach($inputData['claimants'] as $ckey => $claimant_data) {

                if($usr->id > 0) {
                    $usr->address = $claimant_data['address1'];
                    $usr->address1 = $claimant_data['address2'];
                    $usr->city = $claimant_data['city'];
                    $usr->pincode = $claimant_data['pincode'];
                    $usr->state = $claimant_data['state'];
                    $usr->country = $claimant_data['country'];
                    $usr->save();
                }

                
                $involedUser = InvoledUser::where(['userPlanId' => $med->id, 'userEmail' => $claimant_data['email']])->get()->toArray();
                
                if(!empty($involedUser)) {
                    $dataToInsert = [
                        // 'name' => isset($claimant_data['name']) ? $claimant_data['name'] : "",
                        // 'userEmail' => isset($claimant_data['email']) ? $claimant_data['email'] : "",
                        // 'userPhone' => isset($claimant_data['phone']) ? $claimant_data['phone'] : "",
                        'userPlanId' => $med->id,
                        'address1' => isset($claimant_data['address1']) ? $claimant_data['address1'] : "",
                        'address2' => isset($claimant_data['address2']) ? $claimant_data['address2'] : "",
                        'city' => isset($claimant_data['city']) ? $claimant_data['city'] : "",
                        'pincode' => isset($claimant_data['pincode']) ? $claimant_data['pincode'] : "",
                        'state' => isset($claimant_data['state']) ? $claimant_data['state'] : "",
                        'country' => isset($claimant_data['country']) ? $claimant_data['country'] : "",
                        'isClaimant' => 0
                    ];
                    $add_claimant = DB::table('user_involved_in_agreement')->where('id', $involedUser[0]['id'])->update($dataToInsert);
                    
                } else {
                        $add_claimant = new InvoledUser();
                        $add_claimant->userEmail = isset($claimant_data['email']) ? $claimant_data['email'] : "";
                        $add_claimant->name = isset($claimant_data['name']) ? $claimant_data['name'] : "";
                        $add_claimant->userPhone = isset($claimant_data['phone']) ? $claimant_data['phone'] : "";
                        $add_claimant->userPlanId = $med->id;
                        $add_claimant->address1 = isset($claimant_data['address1']) ? $claimant_data['address1'] : "";
                        $add_claimant->address2 = isset($claimant_data['address2']) ? $claimant_data['address2'] : "";
                        $add_claimant->city = isset($claimant_data['city']) ? $claimant_data['city'] : "";
                        $add_claimant->pincode = isset($claimant_data['pincode']) ? $claimant_data['pincode'] : "";
                        $add_claimant->state = isset($claimant_data['state']) ? $claimant_data['state'] : "";
                        $add_claimant->country = isset($claimant_data['country']) ? $claimant_data['country'] : "";
                        $add_claimant->isClaimant = 0;
                        $add_claimant->save();
                }
                
                
            }


            //add responding party

            foreach($inputData['respondants'] as $rkey => $resp_data) {
                $respUser = InvoledUser::where(['userPlanId' => $med->id, 'userEmail' => $resp_data['email']])->orderByDesc('id')->limit(1)->first();
                
                $isClaimant_count = InvoledUser::select('isClaimant')->where('isClaimant', '!=', 0)->orderBy('isClaimant', 'desc')->first()->toArray();
                
                if(!empty($respUser)) {
                    $dataToRespInsert = [
                        // 'name' => isset($resp_data['name']) ? $resp_data['name'] : "",
                        // 'userEmail' => $resp_data,
                        // 'userPhone' => $resp_array['rphones'][$rkey],
                        'userPlanId' => $med->id,
                        'address1' => isset($resp_data['address1']) ? $resp_data['address1'] : "",
                        'address2' => isset($resp_data['address2']) ? $resp_data['address2'] : "",
                        'city' => isset($resp_data['city']) ? $resp_data['city'] : "",
                        'pincode' => isset($resp_data['city']) ? $resp_data['city'] : "",
                        'state' => isset($resp_data['state']) ? $resp_data['state'] : "",
                        'country' => isset($resp_data['country']) ? $resp_data['country'] : "",
                        'isClaimant' => $respUser['isClaimant']
                    ];
                    $add_resp = DB::table('user_involved_in_agreement')->where('userEmail', $resp_data)->update($dataToRespInsert);
                
                } else {
                    $add_resp = new InvoledUser();
                    $add_resp->name = isset($resp_data['name']) ? $resp_data['name'] : "";
                    $add_resp->userEmail = isset($resp_data['email']) ? $resp_data['email'] : "";
                    $add_resp->userPhone = isset($resp_data['phone']) ? $resp_data['phone'] : "";
                    $add_resp->userPlanId = $med->id;
                    $add_resp->address1 = isset($resp_data['address1']) ? $resp_data['address1'] : "";
                    $add_resp->address2 = isset($resp_data['address2']) ? $resp_data['address2'] : "";
                    $add_resp->city = isset($resp_data['city']) ? $resp_data['city'] : "";
                    $add_resp->pincode = isset($resp_data['pincode']) ? $resp_data['pincode'] : "";
                    $add_resp->state = isset($resp_data['state']) ? $resp_data['state'] : "";
                    $add_resp->country = isset($resp_data['country']) ? $resp_data['country'] : "";
                    $add_resp->isClaimant = $isClaimant_count['isClaimant'] + 1;
                    $add_resp->joinCode = $this->joinCode();
                    $add_resp->save();
                }
            }


            $letter = $this->requestLetter($med->id);
            $med->request_letter = $letter;
            $med->save();
            $d = [
                'event' => 'SUBMIT_FORM',
                'case_id' => $med->id,
            ];

            $cid = "M" . sprintf("%06d", $med->id);
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $med->id)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            Common_function::MedNotification($med->id, "SUBMIT_FORM", $userId, null, $inv_id);

            $e = Email::send($d, $usr->email, env('EMAIL_L1', ''), ['-caseId-' => $cid,], $usr->first_name . ' ' . $usr->last_name);

            $data['caseid'] = $med->id;
            $result['success'] = true;
            $result['message'] = "Case registered successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        
    }

    public function newCaseOriginal(Request $request) {
        try{
            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            $userId = $jwtData->data->userid;

            // Inputs
            //$userId = 50;
            $category = $request->input('category');
            $noofparties = $request->input('noofparties');
            $amount = $request->input('amount');
            $issue = $request->input('issue');
            $proposedsolution = $request->input('proposedsolution');
            $refid = $request->input('refid');
            $useradd = $request->input('useradd');
            $useradd1 = $request->input('useradd1');
            $usercity = $request->input('usercity');
            $userpincode = $request->input('userpincode');
            $userstate = $request->input('userstate');
            $usercountry = $request->input('usercountry');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $name = $request->input('name');
            $fulladd = $request->input('fulladd');

            $validator = Validator::make($request->all(), [
                'category' => 'required',
                'noofparties' => 'required',
                'amount' => 'required',
                'issue' => 'string',
                'proposedsolution' => 'string',
                'useradd' => 'string',
                'useradd1' => 'string',
                'usercity' => 'string',
                'userpincode' => 'string',
                'userstate' => 'string',
                'usercountry' => 'string',
                'email' => 'array',
                'phone' => 'array',
                'name' => 'array',
                'fulladd' => 'array'
            ]);


            if ($validator->fails()) {

                $errors = $validator->errors()->all();

                $result['success'] = false;
                $result['message'] = implode(', ', $errors);
                $result['error'] = $validator->errors();
                return response()->json($result, 422);
            }



            $med = new MedCase();

            $med->userid = $userId;

            $med->disputeCategory = $category;

            $med->noOfParties = $noofparties;

            $med->amount = $amount;

            $med->confirm_status = 0;

            $med->created_at = date('Y-m-d H:i:s');

            $med->updated_at = date('Y-m-d H:i:s');

            $med->save();

            

            //fetch all involved users

            $InvoledUser = InvoledUser::where(['userPlanId' => $med->id])->where('isClaimant', '<>', '0')->get()->toArray();


            if ($InvoledUser == null) {

                $med->issue = $issue;
                $med->proposedSolution = $proposedsolution;
                $med->confirm_status = 0;
                $med->bulk_flag = 0;
                $med->ref_id = $refid;
                $med->save();

            }

            $usr = User::find($userId);

            //if (Auth::user()->address == '') {
                $usr->address = $useradd;
                $usr->address1 = $useradd1;
                $usr->city = $usercity;
                $usr->pincode = $userpincode;
                $usr->state = $userstate;
                $usr->country = $usercountry;
                $usr->save();
            //}


            $inv = InvoledUser::where(['userPlanid' => $med->id, 'userId' => $usr->id])->first();

            if (!$inv) {

                $inv = new InvoledUser();
                $inv->userId = $usr->id;
                $inv->userPlanId = $med->id;
                $inv->userEmail = $usr->email;
                $inv->userPhone = $usr->mobile_number;
                $inv->name = ucfirst($usr->first_name) . ' ' . ucfirst($usr->last_name);
                $inv->address1 = $usr->address;

                if ($usr->address1 == '') {
                    $usr->address1 = '';
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


            $respond = 0;

            for ($i = 0; $i < count($email); $i++) {

                if ($noofparties[$i] == 0) {

                    $findUser = User::where(['email'=> $email[$i], 'role' => 0])->first();

                    $inv = new InvoledUser();
                    $inv->userId=isset($findUser->id) ? $findUser->id : 0;
                    $inv->userPlanId = $med->id;
                    $inv->userEmail = $email[$i];
                    $inv->userPhone = $phone[$i];
                    $inv->name = $name[$i];
                   
                    $inv->fulladdress = $fulladd[$i];
                    $inv->isClaimant = '0';
                    $inv->isOnboarded = '1';
                    $inv->created_at = date('Y-m-d H:s:i');
                    $inv->updated_at = date('Y-m-d H:s:i');
                    $inv->save();
                } else {
                    $inv = new InvoledUser();
                    $inv->userPlanId = $med->id;
                    $inv->userEmail = $email[$i];
                    $inv->userPhone = $phone[$i];
                    $inv->name = $name[$i];
                    $inv->joinCode = $this->joinCode();
                    $inv->fulladdress = $fulladd[$i];
                    $inv->isClaimant = $respond + 1;

                    $inv->created_at = date('Y-m-d H:s:i');
                    $inv->updated_at = date('Y-m-d H:s:i');

                    $inv->save();
                    $respond++;
                }
            }


            $letter = $this->requestLetter($med->id);
            $med->request_letter = $letter;
            $med->save();
            $d = [
                'event' => 'SUBMIT_FORM',
                'case_id' => $med->id,
            ];

            $cid = "M" . sprintf("%06d", $med->id);
            $inv_id = "";
            $inv = InvoledUser::select('id')->where('userPlanId', $med->id)->get();
            foreach ($inv as $v) {
                if ($inv_id == "") {
                    $inv_id = $v->id;
                } else {
                    $inv_id = $inv_id . "," . $v->id;
                }
            }
            Common_function::MedNotification($med->id, "SUBMIT_FORM", $userId, null, $inv_id);

            $e = Email::send($d, $usr->email, env('EMAIL_L1', ''), ['-caseId-' => $cid,], $usr->first_name . ' ' . $usr->last_name);

            $data['caseid'] = $med->id;
            $result['success'] = true;
            $result['message'] = "Case raised successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "New case registration failded";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }



    public function requestLetter($id)
    {
        // $data["mediator"] = User::find($medid);
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["ini"] = InvoledUser::select('user_involved_in_agreement.*', 'usr.organization', 'usr.signature_photo')
            ->leftJoin('users as usr', DB::raw('usr.id'), '=', DB::raw('user_involved_in_agreement.userId'))
            ->where("userPlanId", "=", $id)->where('isClaimant', 0)->first();
        $data["res"] = InvoledUser::where("userPlanId", "=", $id)->where('isClaimant', '<>', 0)->first();
        $pdf = PDF::loadView('pdf.request_letter', $data);
        $name = 'request_letter_CID' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
        // Storage::put('public/mediation/' . $data["case"]->id . '/' . $name, $pdf->output());
        $uploadS3 = $this->uploadOnAWSDirect($finalFilePath, $savePath, $pdf);
        return $name;
    }


    public function joinCode()
    {

        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < 8; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }

        return $string;
    }


    public function getNotifications() {
        $data = Notification::userNotification();
        
        $result['success'] = true;
        $result['message'] = "User notifications fetched successfully.";
        $result['data'] = $data;
        return response()->json($result, 200);
    }


    public function caseTrack(Request $request) {
        try {
            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));
            //$userId = $jwtData->data->userid;

            $validator = Validator::make($request->all(), [
                'caseid' => 'required|integer'
            ]);

            //Input
            $caseid = $request->input('caseid');

            $email = EmailTrack::getByCaseId($caseid);

            $mediator = Mediators_mediation_cases_status::select("email", "username", "mobile_number")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                ->where("mediators_mediation_cases_status.mediation_case_id", "=", $caseid)
                ->where("mediators_mediation_cases_status.status", "=", 1)
                ->first();

            $data['caseid'] = $caseid;
            $data['email'] = $email;
            $data['mediator'] = $mediator;

            $result['success'] = true;
            $result['message'] = "Track loaded successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case track not loaded.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function caseJoin(Request $request) {
        try {

            $token = $request->cookie('auth_token');
            if (!$token) {

                $result['success'] = false;
                $result['message'] = 'Unauthorized: Missing token';
                $result['error'] = 'Unauthorized: Missing token';
                return response()->json($result, 401);
            }

            $JWT_KEY = env('JWT_KEY');
            $jwtData = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));

            
            $userId = $jwtData->data->userid;

            $code = $request->input('joincode');

            // $email = Auth::user()->email;
            // $phone = Auth::user()->mobile_number;
            $email = $jwtdata->data->email;

            $phone = $jwtdata->data->phone;
            $name = $jwtdata->data->name;


            $InvoledUser = InvoledUser::where(['joincode' => $code])->where(function ($q) use ($email, $phone) {
                $q->orWhere('userEmail', $email)->orWhere('userPhone', $phone);
            })->first();

            
            if (!$InvoledUser) {
                $result['success'] = false;
                $result['message'] = "Invalid data.";
                $result['error'] = 'Invalid data.';
                return response()->json($result, 500);
            }

            $case = MedCase::where(['id' => $InvoledUser->userPlanId, 'confirm_status' => 1])->first();

            if (!$case) {
                $result['success'] = false;
                $result['message'] = "No cases found with these data.";
                $result['error'] = "No cases found with these data.";
                return response()->json($result, 500);
            }

            $InvoledUser->joincode = null;
            $InvoledUser->isOnboarded = '1';
            $InvoledUser->onboardedDate = now();
            $InvoledUser->userid = $userId;
            //$InvoledUser->userid = 0;
            if ($InvoledUser->name == null) {
                //$InvoledUser->name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $InvoledUser->name = $name;
            }
            if ($InvoledUser->userEmail == null) {
                // /$InvoledUser->userEmail = Auth::user()->email;
                $InvoledUser->userEmail = $email;
            }
            if ($InvoledUser->userPhone == null) {
                // /$InvoledUser->userPhone = Auth::user()->mobile_number;
                $InvoledUser->userPhone = $phone;
            }

             $d = [
                'event' => 'ONBOAR_USER',
                'case_id' => $InvoledUser->userPlanId,
            ];
            if ($InvoledUser->save()) {
                $mediatorNoti = Mediators_mediation_cases_status::select("email", "username", "mobile_number", "users.id")->join("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
                    ->where("mediators_mediation_cases_status.mediation_case_id", "=", $InvoledUser->userPlanId)
                    ->where("mediators_mediation_cases_status.status", "=", 1)
                    ->first();
                $inv_id = "";
                $inv = InvoledUser::select('id')->where('userPlanId', $InvoledUser->userPlanId)->get();
                foreach ($inv as $v) {
                    if ($inv_id == "") {
                        $inv_id = $v->id;
                    } else {
                        $inv_id = $inv_id . "," . $v->id;
                    }
                }

                Common_function::MedNotification($InvoledUser->userPlanId, "ONBOAR_USER", $userId, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id);

                //fetch init parry
                $mid = "M" . sprintf("%06d", $InvoledUser->userPlanId);

                $InvoledUserP1 = InvoledUser::where(['isClaimant' => '0', 'userPlanId' => $InvoledUser->userPlanId])->first();

                $party_name = $InvoledUser->name;

                $e = Email::send($d, $InvoledUserP1->userEmail, env('L7_UPON_SUCCESSFUL_ONBOARDING_OF_ANY_COUNTER_PARTY', ''), ['-caseid-' => $mid, '-name-' => $party_name], $InvoledUserP1->name);
                
                $data['code'] = $code;
                $data['case'] = $case;

                $result['success'] = true;
                $result['message'] = "Join code is correct.";
                $result['data'] = $data;
                return response()->json($result, 200);
            }
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case updation process is failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }
}
