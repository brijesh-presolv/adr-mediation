<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

use App\Http\Helpers\SendGrid;
use App\Http\Helpers\Whatsapp;
use App\Models\InvoledUser;

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

    protected function redirectTo()
    {

        if (Auth::check() && (Auth::user()->role == 0)) {

            if (Auth::user()->emailotp != null) {

                return route('verify');
            } else {
                return route('user.dashboard');
            }
        } else if (Auth::check() && (Auth::user()->role == 1)) {

            if (Auth::user()->emailotp != null) {

                return route('verify');
            }
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
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'mobile_number' => ['required', 'string', 'max:255', 'unique:users'],
            'organization' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'actype' => ['required'],

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

        if ($data['actype'] == 1) {

            $role = 0;
        } else if ($data['actype'] == 2) {
            $role = 1;
        }



        $InvoledUser = InvoledUser::where(['userEmail' => $data['email']])->first();


        if ($InvoledUser) {


            return User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'mobile_number' => $data['mobile_number'],
                'organization' => $data['organization'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $role,
                'emailotp' => rand('100000', '999999'),
                'smsotp' => rand('100000', '999999'),
                'isActive' => 1,
                'status' => 1,

            ]);
        } else {

            return User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'username' => $data['username'],
                'mobile_number' => $data['mobile_number'],
                'organization' => $data['organization'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $role,
                'emailotp' => rand('100000', '999999'),
                'smsotp' => rand('100000', '999999'),
            ]);
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        // dd();


        $d = [
            'event' => 'VARIFY_EMAIL',
            'userid' => $user->id,
        ];

        $authKey = env('SMS_AUTH_KEY', '');
        $flowId = env('SMS_FLOW_KEY', '');
        $url = env('SMS_FLOW_API', '');
        $senderId = "Prsolv";
        $mobileNumber = "+91" . $user->mobile_number;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"flow_id\": \"$flowId\",\n  \"sender\": \"$senderId\",\n  \"mobiles\": \"$mobileNumber\",\n  \"otp\": \"$user->smsotp\"\n  }",
            CURLOPT_HTTPHEADER => [
                "authkey: {$authKey}",
                "content-type: application/JSON"
            ],
        ]);

        $response = curl_exec($ch);


        // dd($response);
        //Print error if any
        if (curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        if ($user->role == '0') {
            $email = SendGrid::send($d, $user->email, env('EMAIL4_RESENDOTP_OF_USER', ''), ['-otp-' => strval($user->emailotp)]);
            
        } else if ($user->role == '1') {

            $email = SendGrid::send($d, $user->email, env('EMAIL5_RESENDOTP_OF_MEDIATOR', ''), ['-otp-' => strval($user->emailotp)], $user->name);
        }

        // if (!Auth::user()->isActive) {
        //     Auth::logout();
        //     return redirect('login')->with('warning','Account Under Review.');
        // }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }
}
