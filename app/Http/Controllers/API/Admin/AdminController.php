<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedCase;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Helpers\Curl;
use App\Models\ManageSession;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\Token;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller 
{

    public function dashboard(Request $request){

        $result['success'] = true;
        return response()->json($result, 200);
    }

    public function totalCaseCount() {
        $allCasesCount = 0;
        $allCasesCount = MedCase::count();

        $result['success'] = true;
        $result['message'] = "Total case count fetched successfully.";
        $result['data'] = $allCasesCount;
        return response()->json($result, 200);
    }

    public function newCaseCount() {
        $newCount = 0;
        $newCount = MedCase::where("confirm_status", 0)->count();

        $result['success'] = true;
        $result['message'] = "New case count fetched successfully.";
        $result['data'] = $newCount;
        return response()->json($result, 200);
    }

    public function ongoingCaseCount() {
        $ongoingCount = 0;
        $ongoingCount = MedCase::where("confirm_status", 1)->count();

        $result['success'] = true;
        $result['message'] = "Ongoing case count fetched successfully.";
        $result['data'] = $ongoingCount;
        return response()->json($result, 200);
    }

    public function resolvedCaseCount() {
        $resolvedCount = 0;
        $resolvedCount = MedCase::where("case_status", 6)->where("confirm_status", 2)->count();

        $result['success'] = true;
        $result['message'] = "Resolved case count fetched successfully.";
        $result['data'] = $resolvedCount;
        return response()->json($result, 200);
    }

    public function unresolvedCaseCount() {
        $unresolvedCount = 0;
        $unresolvedCount = MedCase::where("case_status", 7)->where("confirm_status", 2)->count();

        $result['success'] = true;
        $result['message'] = "Unresolved case count fetched successfully.";
        $result['data'] = $unresolvedCount;
        return response()->json($result, 200);
    }

    public function rejectedCaseCount() {
        $rejectedCount = 0;
        $rejectedCount = MedCase::where("confirm_status", 3)->count();

        $result['success'] = true;
        $result['message'] = "Rejected case count fetched successfully.";
        $result['data'] = $rejectedCount;
        return response()->json($result, 200);
    }

    public function withdrawnCaseCount() {
        $withdrawnCount = 0;
        $withdrawnCount = MedCase::where("case_status", 5)->where("confirm_status", 2)->count();

        $result['success'] = true;
        $result['message'] = "Withdrawn case count fetched successfully.";
        $result['data'] = $withdrawnCount;
        return response()->json($result, 200);
    }
}
