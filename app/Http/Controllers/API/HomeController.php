<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SendGrid;
use App\Http\Helpers\SendGrid as Email;
use App\Http\Helpers\Whatsapp;
use App\Http\Helpers\Common_function;
use App\Models\InvoledUser;
use App\Models\MedCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\ManageSession;
use App\Models\UserStopWhatsapp;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use App\Http\Controllers\API\PaymentController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller 
{

    public function getLanguages(Request $request){

        try{
            
            $languages = DB::table('languages')->orderBy('name')->get();

            $result['success'] = true;
            $result['message'] = "Data fetched successfully.";
            $result['data'] = $languages;
            return response()->json($result, 200);

        } catch (Exception $e) {

            $result['success'] = false;
            $result['message'] = "Data loading failed.";
            $result['error'] = $e->getMessage();
            return response()->json($result, 500);
        }
    }

}
