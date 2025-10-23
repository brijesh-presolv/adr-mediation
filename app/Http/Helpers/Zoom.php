<?php
namespace App\Http\Helpers;

use App\Http\Helpers\Curl;
use Illuminate\Http\Request;

class Zoom
{
    /****** Generate Token *************/
    public static function generateZoomToken()
    {
        ini_set('memory_limit', -1);
        $key = env('ZOOM_API_KEY');
        $secret = env('ZOOM_API_SECRET');
        
        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];
        $jwtToken = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        return $jwtToken;
    }


    /**************************** NEW TOKEN OAUTH ***********************************************/
    public static function generateAuthToken() {
        $curl = curl_init();

        $client_id = env('ZOOM_API_KEY');
        $client_secret = env('ZOOM_API_SECRET');


        $basic_Zoom_Auth =base64_encode("{$client_id}:{$client_secret}");

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://zoom.us/oauth/token?grant_type=account_credentials&account_id='.env('ZOOM_OAUTH_ACCOUNT_ID'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic '.$basic_Zoom_Auth,
            //'Cookie: TS01f92dc5=019f2012cab8ac3a8d11c6a71c9fc04c07c1327b114284b1b8ec203c0263e10d9a320016a7c65c8e09400d50ecc6e4cf81161012c8; TS01fdc528=019f2012cab8ac3a8d11c6a71c9fc04c07c1327b114284b1b8ec203c0263e10d9a320016a7c65c8e09400d50ecc6e4cf81161012c8; __cf_bm=kqUg8c0CldB4lb.j8qWQpaA6ULDvmO85saYytCtzmws-1697113968-0-ATt+SPMOwEH7DawLzRbfUjJ1LcYJCURxAm+anfz91Dn7UNO48vxNwpVpe8cXK1UDMIiwli8K42hLWWc9viTlpyY=; _zm_chtaid=217; _zm_csp_script_nonce=UO_o_hEASHGJ6J2LUMrNig; _zm_ctaid=QwDW2FgzSkepzmmd6HK3iQ.1697110165187.5ed1c18f8f05c0a583711d73b0b8e052; _zm_currency=USD; _zm_mtk_guid=3c1112b8f4074e5bb93b7baec09669f3; _zm_page_auth=us02_c_FfRk9NCiS3GB__nj4R0mgA; _zm_ssid=us02_c_88rplQ0cR_OIa6693LFFbg; _zm_visitor_guid=3c1112b8f4074e5bb93b7baec09669f3; cred=5C662D8C7E06D6B1612CDFE81097A933'
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $final_response = json_decode($response);
        return $final_response->access_token;
    }
    /**************************** NEW TOKEN OAUTH ***********************************************/


    /******************************** Create Zoom Meeting API : START *****************************************/
    public static function createZoomMeeting($case_id, $note, $date_format_api, $end_date_format_api){

        //$zoom_token =  self::generateZoomToken(); // token
        $zoom_token =  self::generateAuthToken(); // Oauth token
        /*****************************/

        $Zoom_Account_User=env('Zoom_Account_User');
        $Zoom_mail=env('Zoom_mail');

        $curl = curl_init(); 
        $c_url = env('ZOOM_API_URL').'users/'.env('Zoom_Account_User').'/meetings';
        curl_setopt_array($curl, array(
            CURLOPT_URL => $c_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                    "agenda": "'.$note.'",
                    "default_password": false,
                    "duration": 60,
                    "password": "123456",
                    "pre_schedule": false,
                    "recurrence": {
                        "end_date_time": "'.$end_date_format_api.'",
                        "end_times": 7,
                        "monthly_day": 1,
                        "monthly_week": 1,
                        "monthly_week_day": 1,
                        "repeat_interval": 1,
                        "type": 1,
                        "weekly_days": "1"
                    },
                    "schedule_for": "'.$Zoom_mail.'",
                    "settings": {
                        "additional_data_center_regions": [
                        "TY"
                        ],
                        "allow_multiple_devices": true,
                        "alternative_hosts": "",
                        "alternative_hosts_email_notification": true,
                        "approval_type": 2,
                        "approved_or_denied_countries_or_regions": {
                        "approved_list": [
                            ""
                        ],
                        "denied_list": [
                            ""
                        ],
                        "enable": true,
                        "method": "approve"
                        },
                        "audio": "both",
                        "authentication_domains": "",
                        "authentication_exception":  [
                        {
                            "email": "'.$Zoom_mail.'",
                            "name": "Mediation Team"
                        }
                        ],
                        "authentication_option": "",
                        "auto_recording": "none",
                        "breakout_room": {
                        "enable": true,
                        "rooms": [
                            {
                            "name": "room1",
                            "participants": [
                                ""
                            ]
                            }
                        ]
                        },
                        "calendar_type": 1,
                        "close_registration": false,
                        "contact_email": "",
                        "contact_name": "",
                        "email_notification": true,
                        "encryption_type": "enhanced_encryption",
                        "focus_mode": true,
                        "host_video": false,
                        "join_before_host": false,
                        "language_interpretation": {
                        "enable": true,
                        "interpreters": [
                            {
                            "email": "'.$Zoom_mail.'",
                            "languages": "US,FR"
                            }
                        ]
                        },
                        "meeting_authentication": false,
                        "meeting_invitees": [
                        {
                            "email": ""
                        }
                        ],
                        "mute_upon_entry": false,
                        "participant_video": false,
                        "private_meeting": false,
                        "registrants_confirmation_email": true,
                        "registrants_email_notification": true,
                        "registration_type": 1,
                        "show_share_button": true,
                        "use_pmi": false,
                        "waiting_room": true,
                        "watermark": false,
                        "host_save_video_order": false,
                        "alternative_host_update_polls": true
                    },
                    "start_time": "'.$date_format_api.'",
                    "template_id": "Dv4YdINdTk+Z5RToadh5ug==",
                    "timezone": "Europe/London",
                    "topic": "Mediation Session Created for Case - M'.sprintf("%06d", $case_id).'",
                    "tracking_fields": [
                        {
                        "field": "field1",
                        "value": "value1"
                        }
                    ],
                    "type": 2
                    }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$zoom_token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        /*****************************/
        return $response;
    }
    /******************************** Create Zoom Meeting API : END *****************************************/

    
    
    
    
    /******************************** Zoom Invitation API : START *****************************************/
    public static function zoomInvitation($meeting_id){
        //$zoom_token =  self::generateZoomToken(); // token
        $zoom_token =  self::generateAuthToken(); // Oauth token
        /*********************************/
        $curl = curl_init();
        $c_url = env('ZOOM_API_URL').'meetings/'.$meeting_id.'/invitation';

        curl_setopt_array($curl, array(
        CURLOPT_URL => $c_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$zoom_token
        ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        /*************************************/
        return $response;
    }
    /******************************** Zoom Invitation API : END *****************************************/



    /******************************** Update Zoom Meeting API : START *****************************************/
    public static function updateZoomMeeting($zoom_id, $case_id, $date_format_api, $end_date_format_api, $note){
       // $zoom_token =  self::generateZoomToken(); // token
       $zoom_token =  self::generateAuthToken(); // Oauth token
        /*************************************/
        $Zoom_Account_User=env('Zoom_Account_User');
        $Zoom_mail=env('Zoom_mail');

        $curl = curl_init();
        $c_url = env('ZOOM_API_URL').'meetings/'.$zoom_id;
        curl_setopt_array($curl, array(
        CURLOPT_URL => $c_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
        "schedule_for": "'.$Zoom_mail.'",
        "agenda": "'.$note.'",
        "duration": 60,
        "password": "123456",
        "pre_schedule": false,
        "recurrence": {
            "end_date_time": "'.$end_date_format_api.'",
            "end_times": 7,
            "monthly_day": 1,
            "monthly_week": 1,
            "monthly_week_day": 1,
            "repeat_interval": 1,
            "type": 1,
            "weekly_days": "1"
        },
        "settings": {
            "allow_multiple_devices": true,
            "alternative_hosts": "",
            "alternative_hosts_email_notification": true,
            "alternative_host_update_polls": true,
            "approval_type": 2,
            "approved_or_denied_countries_or_regions": {
            "approved_list": [
                ""
            ],
            "denied_list": [
                ""
            ],
            "enable": true,
            "method": "approve"
            },
            "audio": "both",
            "authentication_domains": "",
            "authentication_exception": [
            {
                "email": "'.$Zoom_mail.'",
                "name": "Mediation Team",
                "join_url": ""
            }
            ],
            "authentication_name": "",
            "authentication_option": "",
            "auto_recording": "none",
            "breakout_room": {
            "enable": true,
            "rooms": [
                {
                "name": "room1",
                "participants": [
                    ""
                ]
                }
            ]
            },
            "calendar_type": 1,
            "close_registration": false,
            "contact_email": "",
            "contact_name": "",
            "custom_keys": [
            {
                "key": "key1",
                "value": "value1"
            }
            ],
            "email_notification": true,
            "encryption_type": "enhanced_encryption",
            "focus_mode": true,
            "global_dial_in_numbers": [
            {
                
            }
            ],
            "host_video": false,
            "join_before_host": false,
            "language_interpretation": {
            "enable": true,
            "interpreters": [
                {
                "email": "'.$Zoom_mail.'",
                "languages": "US,FR"
                }
            ]
            },
            "meeting_authentication": false,
            "mute_upon_entry": false,
            "participant_video": false,
            "private_meeting": false,
            "registrants_confirmation_email": true,
            "registrants_email_notification": true,
            "registration_type": 1,
            "show_share_button": true,
            "use_pmi": false,
            "waiting_room": true,
            "watermark": false,
            "host_save_video_order": false,
            "meeting_invitees": [
            {
                "email": ""
            }
            ]
        },
        "start_time": "'.$date_format_api.'",
        "template_id": "5Cj3ceXoStO6TGOVvIOVPA==",
        "timezone": "Europe/London",
        "topic": "Mediation Session Meeting Updated for Case - M'.sprintf("%06d", $case_id).'",
        "tracking_fields": [
            {
            "field": "field1",
            "value": "value1"
            }
        ],
        "type": 2
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$zoom_token
        ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        /*************************************/
        return $response;

    }
    /******************************** Update Zoom Meeting API : END *****************************************/

    
    
    
    /******************************** Delete Zoom Meeting API : START *****************************************/
    public static function deleteZoomMeeting($zoom_id){
        //$zoom_token =  self::generateZoomToken(); // token
        $zoom_token =  self::generateAuthToken(); // Oauth token
            /*******************************/
            $curl = curl_init();

            $c_url = env('ZOOM_API_URL').'meetings/'.$zoom_id;

            curl_setopt_array($curl, array(
            CURLOPT_URL => $c_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$zoom_token
            ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            /*******************************/
            return $response;

    }
    /******************************** Delete Zoom Meeting API : END *****************************************/
}
