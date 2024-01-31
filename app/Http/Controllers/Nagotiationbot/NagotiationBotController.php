<?php

namespace App\Http\Controllers\Nagotiationbot;
use App\Http\Controllers\Controller;

use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\InvoledUser;
use App\Models\MedCase;
use App\Models\Reminder;
use App\Models\WaTemplate;
use App\Models\WhatsappLog;
use App\Models\WhatsappChatbot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NagotiationBotController extends Controller
{  
    public function index($caseid, $payToken)
    {
            $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();

            if(empty($caseData)){
                abort(404);
                exit;
            }

        return view('nagotiationBot/restructure');
    }

    public function replyback($caseid, $payToken)
    {
        $caseData = MedCase::select("*")->where("id", $caseid)->where("payToken", $payToken)->first();

        if(empty($caseData)){
            abort(404);
            exit;
        }

        return view('nagotiationBot/restructure');
    }
}
