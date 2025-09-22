<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController  extends Controller 
{


    public function checkAuth(Request $request)
    {

        if ($request->cookie('auth_token')) {

            $token = $request->cookie('auth_token');
            $JWT_KEY = env('JWT_KEY');
            $jwtdata = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));

            if (!isset($jwtdata->data->userid) || !isset($jwtdata->data->role)) {

                $result['success'] = false;
                $result['message'] = "Invalid request";
                $result['error'] = "Invalid request";
                return response()->json($result, 400);

            }else{

                $data['userid'] = $jwtdata->data->userid;
                $data['role'] = $jwtdata->data->role;
                $data['email'] = $jwtdata->data->email;
                $data['name'] = $jwtdata->data->name;


                $result['success'] = true;
                $result['message'] = "Authorized User.";
                $result['data'] = $data;
                return response()->json($result, 200);
            }
        }else{

                $result['success'] = false;
                $result['message'] = "Invalid request";
                $result['error'] = "Invalid request";
                return response()->json($result, 400);
        }

    }

    public function gentoken(Request $request)
    {

        if ($request->cookie('auth_token')) {

            $token = $request->cookie('auth_token');
            $JWT_KEY = env('JWT_KEY');
            $jwtdata = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));

            if (!isset($jwtdata->data->userid) || !isset($jwtdata->data->role)) {

                $result['success'] = false;
                $result['message'] = "Invalid request";
                $result['error'] = "Invalid request";
                return response()->json($result, 400);
                

            }else{

                $userdata['userid'] = $jwtdata->data->userid;
                $userdata['role'] = $jwtdata->data->role;
                $userdata['email'] = $jwtdata->data->email;
                $userdata['name'] = $jwtdata->data->name;

                $token = Token::createToken($userdata); 
                $data['token']=$token;

                $result['success'] = true;
                $result['message'] = "New token generated.";
                $result['data'] = $data;
                return response()->json($result, 200)
                                    ->cookie(
                                        'auth_token',           // cookie name
                                        $token,       // cookie value
                                        360,                     // minutes
                                        '/',
                                        null,                   // domain (or '.yourdomain.com' if frontend + backend share domain)
                                        true,                   // secure = true (required for cross-site cookies on HTTPS)
                                        true,                   // httpOnly
                                        false,                  // raw
                                        'None'                  // SameSite=None (allow cross-site)
                                    );
            }
        }else{

                $result['success'] = false;
                $result['message'] = "Invalid request";
                $result['error'] = "Invalid request";
                return response()->json($result, 400);

        }

    }

}
