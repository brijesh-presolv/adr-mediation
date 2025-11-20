<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Curl;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\System;
use App\Models\EtrackData;
use App\Models\EmailQue;
use App\Models\EmailTrack;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EmailWebhookController extends Controller
{

    public function webhook(Request $request)
    {
        Log::info('Webhook request received');
        Log::warning("Brevo Webhook All Input", $request->all());

        //$token = $request->header('X-Brevo-Webhook-Token');

        /* if ($token !== env('BREVO_WEB_TOKEN')) {
            
            Log::error("Brevo Webhook Error", ['error' =>  'Webhook request unauthorized']);
            return response()->json(['error' => 'Webhook request unauthorized'], 401);
        } */

        // Get event info from Brevo
        $event      = $request->input('event');
        $email      = $request->input('email');
        $messageId  = $request->input('message-id');
        $ts         = $request->input('ts');  

        // Safety check: messageId is required
        if (!$messageId) {

            Log::warning("Brevo Webhook without messageId", $request->all());
            return response()->json(['error' => 'message-id missing'], 400);
        }

        $emailtrackdata=EmailTrack::select('id')->where('sg_message_id', $messageId)->first();

        if(empty($emailtrackdata)){

            $etrackId=0;

        }else{

            $etrackId=$emailtrackdata->id;
        }

        try {

            $user = new EtrackData();
            $user->etrackId = $etrackId;
            $user->email = $email;
            $user->event = $event;
            $user->timestamp = $ts;
            $user->created_at = now();
            $user->save();

            Log::info("Brevo Webhook Stored", ['message_id' => $messageId, 'event' => $event]);

            return response()->json(['status' => 'ok'], 200);

        } catch (Exception $e) {

            Log::error("Brevo Webhook Error", ['error' => $e->getMessage()]);

            return response()->json(['status' => 'ok'], 200);
        }
    }
}
