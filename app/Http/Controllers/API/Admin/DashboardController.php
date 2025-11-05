<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MedCase;
use App\Models\InvoledUser;
use App\Models\Notification;
use App\Models\Batch;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   

    public function getNotifications(Request $request)
    {
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

            $category = $request->input('category', ''); 

            $view = Notification::where('view', 0)->get();
            foreach ($view as $item) {
                $item->view = 1;
                $item->save();
            }
            $noficationdata = Notification::notificationDatabyctgry($category);
            $data = array();
            $casedata=array();
            $userdata=array();
            if(count($noficationdata) > 0) {

                foreach ($noficationdata as $key => $values) {

                    $caseid="";
                    if(!empty($values->case_id)){

                        $caseid=$values->case_id;

                        $casedata = MedCase::select('id', 'confirm_status', 'case_status')->where('id', $values->case_id)->first();

                    }else if(!empty($values->reg_id)){

                        $userdata = User::select('email', 'isActive', 'role', 'status')->where('id', $values->reg_id)->first();

                    }
                        $data[$key]['id'] = $values->id;
                        $data[$key]['case_id'] ='CID' . sprintf('%06d', $caseid);
                        $data[$key]['reg_id'] = $values->reg_id;
                        $data[$key]['event'] = $values->event;
                        $data[$key]['created_at'] = $values->created_at;
                        $data[$key]['updated_at'] = $values->updated_at;
                        $data[$key]['mediator_id'] = $values->mediator_id;
                        $data[$key]['user_id'] = $values->user_id;
                        $data[$key]['view_mediator'] = $values->view_mediator;
                        $data[$key]['view_user'] = $values->view_user;
                        $data[$key]['category'] = $values->category;
                        $data[$key]['isRead'] = $values->isRead;
                        $data[$key]['action_type'] = $values->action_type;
                        $data[$key]['ititle'] = $values->ititle;
                        $data[$key]['idescription'] = $values->idescription;
                        $data[$key]['email'] = $values->email;
                        $data[$key]['casedata'] =  $casedata;
                        $data[$key]['userdata'] =  $userdata;


                }
            }
            $resultData['notifications']=$data;

            $result['success'] = true;
            $result['message'] = "Notifications fetched successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "Notifications loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }


    public function getNotificationsCounts(Request $request)
    {
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

            $view = Notification::where('view', 0)->get();
            foreach ($view as $item) {
                $item->view = 1;
                $item->save();
            }
            $notificationAll = Notification::notificationData();
            $noficationCaseUpdates = Notification::notificationDatabyctgry(1);
            $noficationDocsUpdates = Notification::notificationDatabyctgry(2);
            $noficationSessionUpdates = Notification::notificationDatabyctgry(3);
            $noficationAccountUpdates = Notification::notificationDatabyctgry(4);

            $notificationAllUnread = Notification::select('id')->where('isAdminRead', "=", 0)->get();
            $noficationCaseUpdatesUnread = Notification::select('id')->where('category', "=", 1)->where('isAdminRead', "=", 0)->get();
            $noficationDocsUpdatesUnread = Notification::select('id')->where('category', "=", 2)->where('isAdminRead', "=", 0)->get();
            $noficationSessionUpdatesUnread = Notification::select('id')->where('category', "=", 3)->where('isAdminRead', "=", 0)->get();
            $noficationAccountUpdatesUnread = Notification::select('id')->where('category', "=", 4)->where('isAdminRead', "=", 0)->get();

            $resultData['notifications']['all']=count($notificationAll);
            $resultData['notifications']['caseUpdates']=count($noficationCaseUpdates);
            $resultData['notifications']['docsUpdates']=count($noficationSessionUpdates);
            $resultData['notifications']['sessionUpdates']=count($noficationSessionUpdates);
            $resultData['notifications']['accountUpdates']=count($noficationSessionUpdates);

            $resultData['notifications']['allUnread']=count($notificationAllUnread);
            $resultData['notifications']['caseUpdatesUnread']=count($noficationCaseUpdatesUnread);
            $resultData['notifications']['docsUpdatesUnread']=count($noficationDocsUpdatesUnread);
            $resultData['notifications']['sessionUpdatesUnread']=count($noficationSessionUpdatesUnread);
            $resultData['notifications']['accountUpdatesUnread']=count($noficationAccountUpdatesUnread);

            $result['success'] = true;
            $result['message'] = "Notifications fetched successfully.";
            $result['data'] = $resultData;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "Notifications loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    public function notificatioMarkread(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id'   => 'required',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors()->all(); 

            $result['success'] = false;
            $result['message'] = implode(', ', $errors);
            $result['error'] = $validator->errors();
            return response()->json($result, 422);
        }

        $id=$request->input('id');

        $notification = Notification::find($id);
        $notification->isAdminRead = 1;
        $notification->save();

        $result['success'] = true;
        $result['message'] = "The notification has been marked as read";
        $result['data'] = $resultData;
        return response()->json($result, 200);
    }

}
