<?php

namespace App\Http\Controllers\Mediator;

use Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
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
        return view('mediator.dashboard');
    }

    public function newrequest() {
        return view('mediator.newrequest');
    }
    public function ongoing() {
        return view('mediator.ongoing');
    }
    public function closed() {
        return view('mediator.closed');
    }
    public function profile() {

        $loginUser = Auth::user()->id;
        $profileData = User::find($loginUser);
        return view('mediator.profile', compact('profileData'));
        
    }
    public function users() {
        return view('mediator.users');
    }

    public function updateProfile(Request $request,$id) {
        // $name = $request->input('stud_name');
        echo "string";
        // DB::update('update student set name = ? where id = ?',[$name,$id]);
        // echo "Record updated successfully.<br/>";
        // echo '<a href = "/edit-records">Click Here</a> to go back.';
    }



}
