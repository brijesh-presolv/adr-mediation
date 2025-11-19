<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Curl;
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
        Log::warning("Brevo Webhook All Input", $request->all());

        $token = $request->header('X-Brevo-Webhook-Token');

        if ($token !== env('BREVO_WEB_TOKEN')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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

        try {
                EtrackData::create([
                    'etrackId' => "1",
                    'email'      => $email,
                    'event'      => $event,
                    'timestamp' => $ts,
                    'created_at' => now(),
                ]);

            Log::info("Brevo Webhook Stored", [
                'message_id' => $messageId,
                'event' => $event
            ]);

            return response()->json(['status' => 'ok'], 200);

        } catch (Exception $e) {

            Log::error("Brevo Webhook Error", [
                'error' => $e->getMessage()
            ]);

            // Always return 200 so Brevo does not retry forcibly
            // We control retries using our own logic
            return response()->json(['status' => 'ok'], 200);
        }
    }
}
