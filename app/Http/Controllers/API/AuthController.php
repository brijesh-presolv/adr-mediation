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

        if ($request->cookie('auth_token') || $request->header('token')) {

            $token = $request->cookie('auth_token');
            $JWT_KEY = env('JWT_KEY');
            $jwtdata = JWT::decode($token, new Key(base64_decode($JWT_KEY), 'HS512'));

            if (!isset($jwtdata->data->id) || !isset($jwtdata->data->role)) {

                $result['success'] = false;
                $result['message'] = "Invalid request";
                $result['error'] = "Invalid request";
                return response()->json($result, 400);

            }else{

                $data['userid'] = $jwtdata->data->id;
                $data['role'] = $jwtdata->data->role;
                $data['userid'] = $jwtdata->data->email;
                $data['name'] = $jwtdata->data->first_name;


                $result['success'] = true;
                $result['message'] = "User registered successfully.";
                $result['data'] = $data;
                return response()->json($result, 200);
            }
        }

    }

}
