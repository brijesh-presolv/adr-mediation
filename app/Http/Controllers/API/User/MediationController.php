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
           
            // Inputs
            $category = $request->input('category');
            $amount = $request->input('amount');
            $issue = $request->input('issue');
            $proposedsolution = $request->input('proposedsolution');
            $application = $request->input('application');
            $isAccept1 = $request->input('isAccept1');
            $isAccept2 = $request->input('isAccept2');
            
            $inputData['claimants']= $request->input('claimants.*');
            $inputData['respondants']= $request->input('respondants.*');

            // $validator = Validator::make($request->all(), [
            //     'claimants' => 'array',
            //     'respondants' => 'array',
            //     'respondants.*.email' => 'required|string|email|unique:user_involved_in_agreement,userEmail'.$med->id,
            //     'isAccept1' => 'required',
            //     'isAccept2' => 'required'
            // ]);

            $validator = Validator::make($request->all(), [
                'claimants' => 'array',
                'respondants' => 'array',
                'isAccept1' => 'required',
                'isAccept2' => 'required'
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

            $med->created_at = date('Y-m-d H:i:s');

            $med->updated_at = date('Y-m-d H:i:s');

            $med->save();


            $usr = User::find($userId);

            // Update consent field
            DB::table('user_involved_in_agreement')
            ->where(['userPlanId' => $med->id, 'userEmail' => $usr->email, 'userId' => $userId])
            ->update(['isAccept1' => $isAccept1, 'isAccept2' => $isAccept2]);
            // Update consent field

            $claimant_duplicates = [];
            $resp_duplicates = [];

            

            foreach($inputData['claimants'] as $ckey => $claimant_data) {

                array_push($claimant_duplicates, $claimant_data['email']);
                if ($this->hasDuplicateEmails($claimant_duplicates)) {
                    
                    $result['success'] = false;
                    $result['message'] = "Email is already used. Please enter new email id.";
                    $result['email'] = $claimant_data['email'];
                    return response()->json($result, 500);
                } else {
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
                                $findClaimantUser = User::where(['email'=> $claimant_data['email'], 'role' => 0])->first();
                                $add_claimant = new InvoledUser();
                                $add_claimant->userId = isset($findClaimantUser->id) ? $findClaimantUser->id : 0;
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
                
            }


            //add responding party

            foreach($inputData['respondants'] as $rkey => $resp_data) {
                $respUser = InvoledUser::where(['userPlanId' => $med->id, 'userEmail' => $resp_data['email']])->orderByDesc('id')->limit(1)->first();
                
               // $isClaimant_count = InvoledUser::select('isClaimant')->where('isClaimant', '!=', 0)->where('userPlanId', $med->id)->orderBy('isClaimant', 'desc')->first();
                
                array_push($resp_duplicates, $resp_data['email']);
                array_push($claimant_duplicates, $resp_data['email']);

                
                
                if ($this->hasDuplicateEmails($resp_duplicates)) {
                    
                    $result['success'] = false;
                    $result['message'] = "Email is already used. Please enter new email id.";
                    $result['email'] = $resp_data['email'];
                    return response()->json($result, 500);
                } else if($this->hasDuplicateEmails($claimant_duplicates)) {
                    $result['success'] = false;
                    $result['message'] = "Email is already used. Please enter new email id.";
                    $result['email'] = $resp_data['email'];
                    return response()->json($result, 500);
                } else {
                    if(!empty($respUser)) {
                        $dataToRespInsert = [
                            'userPlanId' => $med->id,
                            'address1' => isset($resp_data['address1']) ? $resp_data['address1'] : "",
                            'address2' => isset($resp_data['address2']) ? $resp_data['address2'] : "",
                            'city' => isset($resp_data['city']) ? $resp_data['city'] : "",
                            'pincode' => isset($resp_data['city']) ? $resp_data['city'] : "",
                            'state' => isset($resp_data['state']) ? $resp_data['state'] : "",
                            'country' => isset($resp_data['country']) ? $resp_data['country'] : "",
                            //'isClaimant' => $respUser['isClaimant']
                            'isClaimant' => 1
                        ];
                        $add_resp = DB::table('user_involved_in_agreement')->where('id', $respUser['id'])->update($dataToRespInsert);
                
                    } else {
                       // $findUser = User::where(['email'=> $resp_data['email'], 'role' => 0])->first();

                        $add_resp = new InvoledUser();
                        $add_resp->name = isset($resp_data['name']) ? $resp_data['name'] : "";
                        //$add_resp->userId=isset($findUser->id) ? $findUser->id : 0;
                        $add_resp->userId = 0;
                        $add_resp->userEmail = isset($resp_data['email']) ? $resp_data['email'] : "";
                        $add_resp->userPhone = isset($resp_data['phone']) ? $resp_data['phone'] : "";
                        $add_resp->userPlanId = $med->id;
                        $add_resp->address1 = isset($resp_data['address1']) ? $resp_data['address1'] : "";
                        $add_resp->address2 = isset($resp_data['address2']) ? $resp_data['address2'] : "";
                        $add_resp->city = isset($resp_data['city']) ? $resp_data['city'] : "";
                        $add_resp->pincode = isset($resp_data['pincode']) ? $resp_data['pincode'] : "";
                        $add_resp->state = isset($resp_data['state']) ? $resp_data['state'] : "";
                        $add_resp->country = isset($resp_data['country']) ? $resp_data['country'] : "";
                        //$add_resp->isClaimant = $rkey + 1;
                        $add_resp->isClaimant = 1;
                        $add_resp->joinCode = $this->joinCode();
                        $add_resp->save();
                    }
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
            Common_function::MedNotification($med->id, "SUBMIT_FORM", $userId, null, $inv_id, null, 1, "success");

            $e = Email::send($d, $usr->email, env('EMAIL_L1', ''), ['-caseId-' => $cid,], $usr->first_name . ' ' . $usr->last_name);

            $data['caseid'] = $med->id;
            $result['success'] = true;
            $result['message'] = "Case registered successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Case registration process is failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function requestLetter($id)
    {
        $data["case"] = MedCase::where("id", "=", $id)->first();
        $data["ini"] = InvoledUser::select('user_involved_in_agreement.*', 'usr.organization', 'usr.signature_photo')
            ->leftJoin('users as usr', DB::raw('usr.id'), '=', DB::raw('user_involved_in_agreement.userId'))
            ->where("userPlanId", "=", $id)->where('isClaimant', 0)->first();
        $data["res"] = InvoledUser::where("userPlanId", "=", $id)->where('isClaimant', '<>', 0)->first();
        $pdf = PDF::loadView('pdf.request_letter', $data);
        $name = 'request_letter_CID' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
        $savePath = 'mediation_documents/mediation/' . $data["case"]->id;
        $finalFilePath = $savePath . '/' . $name;
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

            $data['caseid'] = "CID" . sprintf("%06d", $caseid);
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

            $usr = User::find($userId);

            $code = $request->input('joincode');
            $isAccept1 = $request->input('isAccept1');
            $isAccept2 = $request->input('isAccept2');

            
            $email = $usr->email;

            $phone = $usr->mobile_number;
            $first_name = $usr->first_name;
            $last_name = $usr->last_name;


            $InvoledUser = InvoledUser::where(['joinCode' => $code])->where(function ($q) use ($email, $phone) {
                $q->orWhere('userEmail', $email)->orWhere('userPhone', $phone);
            })->first();

            
            if (!$InvoledUser) {
                $result['success'] = false;
                $result['message'] = "No user found with given data.";
                $result['error'] = 'No user found with given data';
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

            // Update consent field
            $InvoledUser->isAccept1 = $isAccept1;
            $InvoledUser->isAccept2 = $isAccept2;
            // Update consent field
            
            if ($InvoledUser->name == null) {
                $InvoledUser->name = $first_name . ' ' . $last_name;
            }
            if ($InvoledUser->userEmail == null) {
                $InvoledUser->userEmail = $email;
            }
            if ($InvoledUser->userPhone == null) {
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

                Common_function::MedNotification($InvoledUser->userPlanId, "ONBOAR_USER", $userId, isset($mediatorNoti) ? $mediatorNoti->id : null, $inv_id, null, 1, "success");

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


    public function viewCaseDetails(Request $request) {

        //Input
        $caseid = $request->input('caseid');

        $case = MedCase::select("mediation_case.*", "users.first_name as mfirstname", "users.last_name as mlastname", "mediators_mediation_cases_status.mediator_id as mediator_id", "mediators_mediation_cases_status.status as mediator_status", DB::raw("CONCAT(users.first_name,' ', users.last_name) as mfullname"))
        ->leftJoin("mediators_mediation_cases_status", "mediators_mediation_cases_status.mediation_case_id", "=", "mediation_case.id")
        ->leftJoin("users", "users.id", "=", "mediators_mediation_cases_status.mediator_id")
        ->where('mediation_case.id', '=', $caseid)
        ->first();


        $party_details = InvoledUser::select('user_involved_in_agreement.*', 'users.address as useraddress', 'users.address1 as useraddress1', 'users.pincode as userpincode', 'users.city as usercity', 'users.state as userstate', 'users.country as usercountry')
            ->leftJoin("users", "users.id", "=", "user_involved_in_agreement.userId")
            ->where(['user_involved_in_agreement.userPlanid' => $case->id])->get();

        
        // Party details //
         $claimants = array();
        $respondents = array();

        $ckey = 1;
        $rkey = 1;

        foreach($party_details as $key => $party) {

            if($party->isClaimant == 0){

                $claimants[$ckey++] = [
                    "name"=> $party->name,
                    "email"=> $party->userEmail,
                    "phone"=> $party->userPhone,
                    "address"=> $party->address1,
                    "address2"=> $party->address2,
                    "city"=> $party->city,
                    "pincode"=> $party->pincode,
                    "state"=> $party->state,
                    "country"=> $party->country,
                ];

            }

            
            
            if($party->isClaimant != 0){

                $respondents[$rkey++] = [
                    "name"=> $party->name,
                    "email"=> $party->userEmail,
                    "phone"=> $party->userPhone,
                    "address"=> $party->address1,
                    "address2"=> $party->address2,
                    "city"=> $party->city,
                    "pincode"=> $party->pincode,
                    "state"=> $party->state,
                    "country"=> $party->country,
                ];
            }

           
        }
        
       

        $case->claimants = $claimants;
        $case->respondents = $respondents;
        // Party details //

        $case->invitation = InvitationFiles::where(['case_id' => $case->id])->orderByDesc('id')->get();

        $case->appointment = InvitationFiles::where(['case_id' => $case->id])->where('file_name_mediator_appointment', '!=', null)->orderByDesc('id')->limit(1)->first();

        $case->supporting_document = DB::table('manage_files')->select('manage_files.*', DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"))
            ->join('users', 'users.id', '=', 'manage_files.uploaded_by')
            ->where('manage_files.case_id', $case->id)
            ->get();

        $case->settlement_document = DB::table('document_settlements')->select('document_settlements.*', DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"))
            ->join('users', 'users.id', '=', 'document_settlements.uploaded_by')
            ->where('document_settlements.mediation_case_id', $case->id)
            ->get();


        //$case->mom = DB::table('session_mom')->select("file_name")->where('case_id', $case->id)->get();

        $case->mom = DB::table('session_mom')->select('file_name', DB::raw("CONCAT(users.first_name,' ',users.last_name) as fullname"))
            ->join('users', 'users.id', '=', 'session_mom.uploaded_by')
            ->where('case_id', $case->id)
            ->get();

        $result['success'] = true;
        $result['message'] = "Case details fetched successfully.";
        $result['data'] = $case;
        return response()->json($result, 200);
    }


    public function getIPData(Request $request) {
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
            
            $usr = User::find($userId);

            $data['userid'] = $userId;
            $data['first_name'] = $usr->first_name;
            $data['last_name'] = $usr->last_name;
            $data['name'] = $usr->first_name .' '.$usr->last_name;
            $data['email'] = $usr->email;
            $data['phone'] = $usr->mobile_number;
            $data['address'] = $usr->address;
            $data['address1'] = $usr->address1;
            $data['city'] = $usr->city;
            $data['pincode'] = $usr->pincode;
            $data['state'] = $usr->state;
            $data['country'] = $usr->country;

            $result['success'] = true;
            $result['message'] = "Logged in user data fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Logged in user data not found.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function hasDuplicateEmails(array $emailArray): bool
    {
        $seenEmails = [];
        foreach ($emailArray as $email) {
            // Convert email to lowercase for case-insensitive comparison
            $lowercaseEmail = strtolower($email); 
            if (isset($seenEmails[$lowercaseEmail])) {
                // Duplicate found
                return true; 
            }
            $seenEmails[$lowercaseEmail] = true;
        }
        // No duplicates found
        return false; 
    }
}
