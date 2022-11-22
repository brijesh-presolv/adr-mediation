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


    /******************************** Create Zoom Meeting API : START *****************************************/
    public static function createZoomMeeting($case_id, $note, $date_format_api, $end_date_format_api){

        $zoom_token =  self::generateZoomToken(); // token
        /*****************************/
        $curl = curl_init(); 
        $c_url = env('ZOOM_API_URL').'users/info@presolv360.com/meetings';
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
                    "schedule_for": "info@presolv360.com",
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
                        "audio": "telephony",
                        "authentication_domains": "",
                        "authentication_exception":  [
                        {
                            "email": "info@presolv360.com",
                            "name": "Mediation Team"
                        }
                        ],
                        "authentication_option": "",
                        "auto_recording": "cloud",
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
                        "global_dial_in_countries": [
                        "US"
                        ],
                        "host_video": true,
                        "jbh_time": 0,
                        "join_before_host": false,
                        "language_interpretation": {
                        "enable": true,
                        "interpreters": [
                            {
                            "email": "info@presolv360.com",
                            "languages": "US,FR"
                            }
                        ]
                        },
                        "meeting_authentication": true,
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
                        "waiting_room": false,
                        "waiting_room_options": {
                        "enable": true,
                        "admit_type": 1,
                        "auto_admit": 1,
                        "internal_user_auto_admit": 1
                        },
                        "watermark": false,
                        "host_save_video_order": true,
                        "alternative_host_update_polls": true
                    },
                    "start_time": "'.$date_format_api.'",
                    "template_id": "Dv4YdINdTk+Z5RToadh5ug==",
                    "timezone": "Asia/Calcutta",
                    "topic": "Medaition Session Creted for Case - '.$case_id.'",
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
        $zoom_token =  self::generateZoomToken(); // token
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
        $zoom_token =  self::generateZoomToken(); // token
        /*************************************/
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
        "schedule_for": "info@presolv360.com",
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
            "approval_type": 0,
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
            "audio": "telephony",
            "authentication_domains": "",
            "authentication_exception": [
            {
                "email": "info@presolv360.com",
                "name": "Mediation Team",
                "join_url": ""
            }
            ],
            "authentication_name": "",
            "authentication_option": "",
            "auto_recording": "cloud",
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
            "global_dial_in_countries": [
            "US"
            ],
            "global_dial_in_numbers": [
            {
                
            }
            ],
            "host_video": true,
            "jbh_time": 0,
            "join_before_host": true,
            "language_interpretation": {
            "enable": true,
            "interpreters": [
                {
                "email": "info@presolv360.com",
                "languages": "US,FR"
                }
            ]
            },
            "meeting_authentication": true,
            "mute_upon_entry": false,
            "participant_video": false,
            "private_meeting": false,
            "registrants_confirmation_email": true,
            "registrants_email_notification": true,
            "registration_type": 1,
            "show_share_button": true,
            "use_pmi": false,
            "waiting_room": false,
            "waiting_room_options": {
            "enable": true,
            "admit_type": 1,
            "auto_admit": 1,
            "internal_user_auto_admit": 1
            },
            "watermark": false,
            "host_save_video_order": true,
            "meeting_invitees": [
            {
                "email": ""
            }
            ]
        },
        "start_time": "'.$date_format_api.'",
        "template_id": "5Cj3ceXoStO6TGOVvIOVPA==",
        "timezone": "Asia/Calcutta",
        "topic": "Mediation Session Meeting Updated for Case - '.$case_id.'",
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
        $zoom_token =  self::generateZoomToken(); // token
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
