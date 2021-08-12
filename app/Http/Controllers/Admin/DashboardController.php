<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MedCase;
use App\Models\InvoledUser;

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
        $usersCount = 0;
        $allCasesCount = 0;
        $respondingPartiesCount = 0;
        $ongoingCount = 0;
        $resolvedCount = 0;
        $newCount = 0;
        $usersCount = User::whereIn("role", [0, 1])->count();
        $allCasesCount = MedCase::count();
        $respondingPartiesCount = InvoledUser::where("joinCode", null)->count();
        $resolvedCount = MedCase::where("confirm_status", 2)->count();
        $ongoingCount = MedCase::where("confirm_status", 1)->count();
        $newCount = MedCase::where("confirm_status", 0)->count();
        return view('admin.dashboard', compact('usersCount', 'allCasesCount', 'respondingPartiesCount', 'resolvedCount', 'ongoingCount', 'newCount'));
    }

}
