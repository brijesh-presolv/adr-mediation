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

            $view = Notification::where('view', 0)->get();
            foreach ($view as $item) {
                $item->view = 1;
                $item->save();
            }
            $data = Notification::notificationData();
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

}
