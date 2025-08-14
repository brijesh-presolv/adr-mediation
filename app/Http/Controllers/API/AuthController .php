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

class AuthController  extends Controller 
{


    public function checkAuth(Request $request)
    {
        
        $user = Auth::user(); 

        print_r($user);die();
        
        return response()->json([
            'authenticated' => true,
            'user' => $request->auth_user
        ]);
    }

}
