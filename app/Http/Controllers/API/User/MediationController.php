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

use PDF;
use DateTime;
use DateTimeZone;
use Carbon\Carbon;
use App\Http\Helpers\Zoom;


class MediationController extends Controller 
{
    use UploadTrait;

    public function newCase(Request $request) {
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
        $name = 'request_letter_M' . sprintf('%06d', $data["case"]->id) . time() . '.pdf';
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
}
