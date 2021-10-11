<?php
namespace App\Http\Helpers;
//use App\Http\Helpers\Curl;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Http\Helpers\Token;
use App\Http\Helpers\Curl;
use App\Models\Arbcase;
use PDF;
use App\Models\Arb_notification;


class Common_function
{
      public static function IdfNewUser($d)
    {
        if ($d['type'] != 1) {
            return false;
        }

        $data = [
            'event' => $d['event'],
            'case_type' => $d['type'],
            'case_id' => $d['case_id'],
        ];
       
       $IDFUSER=Config::get('constants.IDFUSER');
        if (self::pushData($IDFUSER, $data, 'POST', 'admin')) {
            return true;
        } else {
            return false;
        }
    }

    public static function ArbLog($cid, $event, $id = '',$uploaded_by='')
    {

        if ($id == '') {
            if ($uploaded_by) {
                $id = $uploaded_by;
            } else {
                $id = 1;
            }
            // $id = $uploaded_by;
        }

        $ntfcn = [
            'userid' => $id,
            'case_id' => $cid,
            'event' => $event,
            'userip' => self::get_client_ip(),
        ];

        Arb_notification::insert($ntfcn);
    }


     public static function IdfNewWitness($d, $e)
    {
        if($d['type'] != 1) 
        {
            return false;
        }

        if(isset($d['event_type']) and $d['event_type'] == 1 and $d['event'] == 'INVKARB_USER') 
        {
            self::IdfNewUser($d);
        }

        $userid = '0';

        if ($e != '') {

            $d['email'] = $e;

           $dta=User::select('*')->where([['email','=',$e]])->first();

            $usrid =  $dta->id;

            if ($usrid != '') {
                $userid = $usrid;
            }
        }

        $content = json_encode($d);

        if (isset($_SESSION['userid'])) {
            $logged_user = $_SESSION['userid'];
        } else {
            $logged_user = 1;
        }

        $clntip = self::get_client_ip();

        if (isset($d['event_type'])) {
            $event_type = $d['event_type'];
        } else {
            $event_type = 2;
        }

        if (isset($d['file'])) {
            $file = $d['file'];
        } else {
            $file = 0;
        }

        $data = [

            'event' => $d['event'],
            'case_type' => $d['type'],
            'case_id' => $d['case_id'],
            'contentid' => hash('sha512', $content),
            'email' => $e,
            'userid' => $userid,
            'logged_user' => $logged_user,
            'clntip' => $clntip,
            'event_type' => $event_type,
            'file' => $file,
        ];

        //print_r($data);exit;


         $IDFWITNESS=Config::get('constants.IDFWITNESS');
        return self::pushData($IDFWITNESS, $data, 'POST', 'admin');
    }

     public static function pushData($url, $data, $type, $role)
    {

        $token = "";

        if ($role == "admin") {
            $token = Token::createToken(['role' => 'admin', 'id' => 1]);
        }

        $res = Curl::getdata($url, $data, $type, $token);

        if ($res) {
            return $res;
        } else {
            return false;
        }
    }

       public static function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

       public static function getsixdigitid($type, $id)
    {


        if ($type == 'sc') {

            return 'A' . sprintf('%06d', $id);
        } else if ($type == 'dc') {
            return 'A' . sprintf('%06d', $id);
        } else if ($type == 'sp') {
            return 'PS-' . sprintf('%06d', $id);
        } else if ($type == 'dp') {
            return 'PD-' . sprintf('%06d', $id);
        }
    }
    

 public static function mres($value)
{
    return preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $value);
}

 public static function isArbaccess($id, $uid)
    {

        $res = Arbcase::getAccessIds($id);

        if (!$res) {
            return false;
        }

        $ids = [];

        $ids[] = 1;

        foreach ($res as $key => $value) {
            $ids[] = $value->arbid;
            $ids[] = $value->userid;
        }

        $accessids = array_unique($ids);


        if (in_array($uid, $accessids)) {

            return true;
        } else {
            return false;
        }
    }


public static function genDisclosure($c, $p, $t, $fname,$uploaded_by)
    {

        //include_once 'pdf/Template/Consent.php';

        $data = Arbcase::getcasebyId($c);
        $data=$data[0];
        $onarb = Arbcase::runArbarbtr($c, $data->user1email, $data->user2email, $data->arbid,$uploaded_by);

        $ad = User::getuserdatabyid($data->arbid);
        $ad = $ad[0];

        $arbadd = '';

        if ($ad->address1 == '') {

            $arbadd = '-';
        } else {

            $arbadd = $ad->address1 . ' ' . $ad->address2 . ' ' . $ad->city . ' ' .
                $ad->pincode . '<br>';

            $arbadd .= $ad->state . ' ' . $ad->country;
        }
        
        $dataa['c']=$data;
        $dataa['on']=$onarb;
        $dataa['add']=$arbadd;
        $dataa['p']=$p;
        $dataa['t']=$t;
        $dataa['pdf']=$fname;

        $pdf = PDF::loadView('pdf.consent', $dataa);
        //return $pdffile = pdf($data, $onarb, $arbadd, $p, $t, $fname);
        //$pdf->save($pdf);
        $pf = "consent".time()."-".$c.".pdf";

        $pdf->save($pf);
        
    }

public static function procorderpdf($c)
    {

       $dataa['c']=$c;

        $pdf = PDF::loadView('pdf.procorder', $dataa);
        //return $pdffile = pdf($data, $onarb, $arbadd, $p, $t, $fname);
        //$pdf->save($pdf);
        $pf = "procorder".time()."-pdf";

        $pdf->save($pf);
    }




}
