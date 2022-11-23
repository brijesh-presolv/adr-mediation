<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InvitationFiles;
use App\Models\InvoledUser;
use App\Models\MedCase;
use App\Models\Mediators_mediation_cases_status;
use App\Models\SupportingDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Helpers\Curl;


class IvrController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    public function acceptcase()
    {
        $date = \Carbon\Carbon::today()->subDays(1);

        $date = $date->format('Y-m-d');

        $cases = MedCase::select('mediation_case.*', 'mediation_status_logs.created_at as cr')

            ->leftJoin("mediation_status_logs", function ($join) {

                $join->on("mediation_status_logs.mediation_case_id", "=", "mediation_case.id");
                //->on("mediation_status_logs.status","=",1);
            })
            ->whereDate('mediation_status_logs.created_at', $date)
            ->where('mediation_status_logs.status', '1')

            ->get();


        $arraydata = array();
        foreach ($cases as $key => $case) {


            $party = InvoledUser::where(['userPlanid' => $case->id])->get();

            $claimant = '';

            $respondent = '';

            $contact = "";

            $i = 0;

            foreach ($party as $k => $v) {

                if ($v->isClaimant == '0' and $i == 0) {


                    $claimant = User::where(['id' => $v->userId])->first();

                    if ($claimant->organization == '') {

                        $claimant = $claimant->first_name . ' ' . $claimant->last_name;
                    } else {

                        $claimant = $claimant->organization;
                    }
                } else if ($i == 1 and $v->isClaimant == '1') {

                    $respondent = $v->name;

                    $contact = $v->userPhone;
                }

                $i++;
            }


            if ($contact != '') {

                $data['contact'] = $contact;


                $data['template'] = 'Hello ' . $respondent . ' you are invited for mediation by ' . $claimant.' for amicably resolving your dispute.';

                $data['auth'] = "MED360AUTH";

                $data['event'] = "ACPTMED_ADM";

                $data['app'] = "P360MED";

                $data['pivrid'] = "61f7db108353a316";

                $data['caseid'] = $case->id;

                $url = "https://presolv360.com/functions/myopout.php";


                $res = Curl::getdata($url, $data, 'POST', 'MED360AUTH');
            }
        }


        exit();



        return response()->json(["code" => 2000, "status" => "success", "data" => $arraydata]);
    }

    public function reminedAcceptcase()
    {
        $date = \Carbon\Carbon::today()->subDays(2);

        $date = $date->format('Y-m-d');

        $cases = MedCase::select('mediation_case.*', 'mediation_status_logs.created_at as cr')

            ->leftJoin("mediation_status_logs", function ($join) {

                $join->on("mediation_status_logs.mediation_case_id", "=", "mediation_case.id");
                //->on("mediation_status_logs.status","=",1);
            })
            ->whereDate('mediation_status_logs.created_at', $date)
            ->where('mediation_status_logs.status', '1')

            ->get();


        $arraydata = array();
        foreach ($cases as $key => $case) {


            $party = InvoledUser::where(['userPlanid' => $case->id])->get();

            $claimant = '';

            $respondent = '';

            $contact = "";

            $i = 0;

            foreach ($party as $k => $v) {

                if ($v->isClaimant == '0' and $i == 0) {


                    $claimant = User::where(['id' => $v->userId])->first();

                    if ($claimant->organization == '') {

                        $claimant = $claimant->first_name . ' ' . $claimant->last_name;
                    } else {

                        $claimant = $claimant->organization;
                    }
                } else if ($i == 1 and $v->isClaimant == '1') {

                    $respondent = $v->name;

                    $contact = $v->userPhone;
                }

                $i++;
     
            }

            if ($contact != '') {

                $data['contact'] = $contact;


                $data['template'] = 'hello ' . $respondent . ' ' . $claimant . ' ne madhyastha ke dwara aapka vivad suljhane ke liye aapko aamantrit kiya hai.';

                $data['auth'] = "MED360AUTH";

                $data['event'] = "ACPTMED_ADM";

                $data['app'] = "P360MED";

                $data['pivrid'] = "6299fe7601f5d759";

                $data['caseid'] = $case->id;

                $url = "https://presolv360.com/functions/myopout.php";

                $res = Curl::getdata($url, $data, 'POST', 'MED360AUTH');
            }
        }





        return response()->json(["code" => 200, "status" => "success", "data" => $arraydata]);
        exit();
    
    }
}
