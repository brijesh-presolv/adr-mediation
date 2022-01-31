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

class DashboardController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        return view('user.dashboard');
    }

    public function pastDateData()
    {
        $date = \Carbon\Carbon::today()->subDays(1);

        $date = $date->format('Y-m-d');
       
        $cases = MedCase::select('mediation_case.*', 'mediators_mediation_cases_status.mediator_id', 'mediators_mediation_cases_status.status', 'mediators_mediation_cases_status.created_at as date')
            ->leftJoin('mediators_mediation_cases_status', DB::raw('mediators_mediation_cases_status.mediation_case_id'), '=', DB::raw('mediation_case.id'))
            ->whereDate('mediators_mediation_cases_status.created_at', $date)->get();
      
        $arraydata = array();
        foreach ($cases as $key => $case) {
            $arraydata[] = [
                "key" => $key + 1,
                "case" => $case,
                "party" => InvoledUser::where(['userPlanid' => $case->id])->get(),
                "mediator" => User::find($case->mediator_id),
                "invitation_files" => InvitationFiles::select('id', 'case_id', 'file_name', 'file_name_mediator_appointment')->where('case_id', $case->id)->orderByDesc('id')->limit(1)->first(),
                "supporting_document" => SupportingDocument::where('case_id', $case->id)->get(),
                'session' => DB::table('manage_session')->where('case_id', $case->id)->get(),
            ];
        }
        return response()->json(["code" => 200, "status" => "success", "data" => $arraydata]);
    }

}
