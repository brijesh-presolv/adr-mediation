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
   

    public function getNotifications()
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
            //$userId = $jwtData->data->userid;

            $view = Notification::where('view', 0)->get();
            foreach ($view as $item) {
                $item->view = 1;
                $item->save();
            }
            $data = Notification::notificationData();

            $result['success'] = true;
            $result['message'] = "Notifications fetched successfully.";
            $result['data'] = $data;
            return response()->json($result, 200);
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = "Notifications loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

    // public function itmNotification(){
    //     $batchName = Batch::get();
    //     return view('admin.case.itmnotification', compact("batchName"));
    // }

    // public function closeCaseNotification(){
    //     $batchName = Batch::get();
    //     return view('admin.case.closecasenotification', compact("batchName"));
    // }

    // public function sessionScheduleNotification(){
    //     $batchName = Batch::get();
    //     return view('admin.case.sessionschedulenotification', compact("batchName"));
    // }

    // public function bulkUploadNotification(){
    //     $batchName = Batch::get();
    //     return view('admin.case.bulkuploadnotification', compact("batchName"));
    // }

    // public function selectplatform(){
    //     return view('admin.case.selectplatform'); 
    // }


    // // check vapt file content
    // public function checkPdfContent(Request $request){
    // //   / dd($request->all());
    //     $file = $request['signature'];
        
    //     $content = file_get_contents($file);
    //     if (preg_match('/\/JS|\/JavaScript|\/OpenAction|XSS/', $content)) {
    //         $msg = "File contains restricted data , please check and re-upload.<br>";
    //         return json_encode(['code' => 200, 'response' => 'err', 'msg' => $msg]);
    //     } else {
    //         return json_encode(['code' => 200, 'response' => 'success']);
    //     }
    // }
    // check vapt file content
}
