<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;

     protected function redirectTo(){

       if (Auth::check() && (Auth::user()->role == 0)) {

        if(Auth::user()->emailotp!=null){
          
          return route('verify');
      } else{
           return route('user.dashboard');
      }

      
        } else if (Auth::check() && (Auth::user()->role == 1)) {
           return route('mediator.dashboard');
        } else if (Auth::check() && (Auth::user()->role == 2)) {
            return route('admin.dashboard');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255','unique:users'],
            'mobile_number' => ['required', 'string', 'max:255','unique:users'],
            'organization' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'actype'=>['required'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        //check if user role

        if($data['actype']==1){

            $role=0;
        } else if($data['actype']==2){
            $role=1;
        }
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'mobile_number' => $data['mobile_number'],
            'organization' => $data['organization'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role'=>$role,
            'emailotp'=>rand('100000','999999'),
            'smsotp'=>rand('100000','999999'),

        ]);
    }


   
}
