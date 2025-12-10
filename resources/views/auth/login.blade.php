<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Presolv360</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileImage" content="{{url('/assert/')}}/img/DisputeManagement.jpg" />

    <meta name="description" content="Online Dispute Resolution platform to conduct arbitration, mediation, conciliation digitally." />

    <meta name="keywords" content="ODR, ADR, Online Dispute Resolution, Alternative Dispute Resolution, Out of Court Settlement, Arbitration, Mediation, Conciliation, Online Arbitration, Online Negotiation, Money Recovery, Legaltech, ODR India" />

    <meta property="og:keywords" content="ODR, ADR, Online Dispute Resolution, Alternative Dispute Resolution, Out of Court Settlement, Arbitration, Mediation, Conciliation, Online Arbitration, Online Negotiation, Money Recovery, Legaltech, ODR India" />
    <meta property="og:description" content="Online Dispute Resolution platform to conduct arbitration, mediation, conciliation digitally." />
    <meta property="og:image" content="{{url('/assert/')}}/img/DisputeManagement.jpg" />
    <meta property="og:image:secure_url" content="https://www.presolv360.com/public/images/DisputeManagement.jpg" />
    <meta property="og:url" content="https://www.presolv360.com">
    <meta property="og:title" content=" {{ config('app.name', 'Medtiator') }} | Dispute resolution made easy" />
    <meta property="og:type" content="article" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Standard Favicon -->
    <link rel="icon" type="image/x-icon" href="{{url('/assert/')}}/img/icon.png" />
    <link rel="stylesheet" type="text/css" href="{{url('/assert/')}}/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('/assert/')}}/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="{{url('/assert/')}}/css/sweetalert2.css">
    <link rel="stylesheet" href="{{url('/assert/')}}/css/style_slide.css">
    <link rel="stylesheet" type="text/css" href="{{url('/assert/')}}/css/home.css">
    <link rel="stylesheet" type="text/css" href="{{url('/assert/')}}/OwlCarousel/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('/assert/')}}/OwlCarousel/dist/assets/owl.theme.default.css">

    <style>
        .alert-bubble {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 18px;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            font-size: 14px;
            font-weight: 600;
            z-index: 9999;
            animation: bounce 1s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-3px); }
        }

                .grecaptcha-badge {
            visibility: hidden;
            height: 0px!important;
        }

        .form-input {
            padding-top: 6px !important;
            padding-bottom: 6px !important;
            font-size: 12px !important;
            font-family: 'Poppins', sans-serif !important;
        }

        .form-label {
            font-size: 12px !important;
            font-family: 'Poppins', sans-serif !important;

        }

        /* Reduce label & small text size */
        .form-small {
            font-size: 12px !important;
        }

        /* Reduce spacing between fields */
        .form-gap {
            margin-bottom: 8px !important;
        }

        /* Reduce container size */
        .form-box {
            max-width: 480px !important;
            padding: 16px !important;
        }

        /* Reduce heading */
        .form-title {
            font-size: 20px !important;
        }
    </style>

    <!-- Bootstrap for Modal (required) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

  <body class="min-h-screen flex flex-col justify-center relative bg-gradient-to-br from-[#eef3f7] via-[#f7f9fb] to-[#ffffff]">

    <!-- Logo -->
    <div class="absolute top-6 left-6 md:left-12 flex items-center">
        <a href="/">
            <img src="{{url('/assert/')}}/img/logo.png" class="h-10" class="img-fluid home-logo">
        </a>
    </div>

  <!-- Login Box With More Top Space -->
  <div class="flex-grow flex items-center justify-center px-4">
        <div class="w-full max-w-md border border-[#c8d6e2] rounded-xl p-8">

            <!-- Title -->
            <div class="flex flex-col items-center mb-2">
                <h4 class="text-1xl font-semibold text-[#0B5386] text-center form-title">Login to your account</h4>
                <p class="text-gray-600 text-sm text-center">Enter your details to login.</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-2">
                <span class="text-gray-700 font-sm form-label">@lang('site.email')*</span>
                <input 
                    type="email" id="email1" name="email"
                    class="form-input mt-1 w-full px-4 py-2 border border-gray-300 rounded-md 
                        focus:ring-2 focus:ring-[#0B5386] @error('email') border-red-500 @enderror"
                    placeholder="example@gmail.com" required>
                    @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <span class="text-gray-700 font-sm form-label">@lang('site.password')*</span>
                    <!-- Input + Eye -->
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password"
                            id="exampleInputPassword1"
                            class="form-input w-full px-4 py-2 pr-10 border border-gray-300 bg-white rounded-md 
                                focus:ring-2 focus:ring-[#0B5386] @error('password') border-red-500 @enderror txtmobile "
                            placeholder="••••••••" 
                            required
                        />

                        <!-- Eye Icon -->
                        <span 
                            class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500"
                            onclick="togglePassword()">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 
                                        2.943 9.542 7-1.274 4.057-5.065 7-9.542 
                                        7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                        </span>
                    </div>
                    @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Privacy Policy -->
                <div class="mb-2 rdivmain">
                    <label class="flex items-start gap-2 text-gray-700 form-label  check_privacypolicy">
                        <input type="checkbox" name="is_agree" id="check_privacypolicy" required class="mt-1">
                        <span class=" form-label">
                            I have read and agree to the
                            <a href="https://presolv360.com/terms_conditions" target="_blank" class="text-[#0B5386] ">Terms & Conditions</a>,
                            <a href="https://presolv360.com/privacy_policy" target="_blank" class="text-[#0B5386] ">Privacy Policy</a> and
                            <a href="https://presolv360.com/cookie_policy" target="_blank" class="text-[#0B5386] ">Cookie Policy</a>.
                        </span>
                    </label>
                    @error('password')
                       <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Button -->
                <div class="flex justify-center">
                    <button 
                        class="py-1.5 px-4 w-32 rounded-lg text-sm font-medium text-white
                            bg-[#0B5386]
                            shadow-md shadow-[#0B5386]/30
                            hover:bg-[#094568]
                            hover:shadow-lg hover:shadow-[#0B5386]/40
                            transition-all duration-300 ease-in-out
                            ulogin">
                        Submit
                    </button>
                </div>

                <!-- Forgot Password -->
                <div class="mt-4 text-center">
                    <button type="button"
                            class="text-[#0B5386] text-sm hover:underline"
                            data-toggle="modal" data-target="#myModal">
                        @lang('site.forgotpassword')
                    </button>
                </div>

                <!-- Forgot username -->
                <div class="text-center">
                    <button type="button"
                            class="text-[#0B5386] text-sm hover:underline"
                            data-toggle="modal" data-target="#myModal2">
                        @lang('site.forgotusername')
                    </button>
                </div>

                <!-- Create Account -->
                <p class="mt-2 text-center text-sm text-gray-600">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-[#0B5386] font-medium hover:underline">Create account</a>
                </p>

            </form>
        </div>
    </div>

    <!-- ============================= -->
    <!--     FORGOT PASSWORD MODAL     -->
    <!-- ============================= -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-6 rounded-xl shadow-lg">

                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-xl font-semibold text-[#0B5386]">Forgot Password</h5>
                    <button type="button" class="close text-xl" data-dismiss="modal">&times;</button>
                </div>
                    <label class="block mb-2">
                        <span class="text-gray-700 font-sm form-label">@lang('site.Enter Username')</span>
                        <input type="email" name="email_pass_reset" id="email_pass_reset"
                               class="form-input mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#0B5386]"
                               placeholder="Username" required>
                        <span id="forgotPassMessage" class="block mt-1 text-xs" style="color:red;"></span>
                    </label>

                    <div class="flex">
                        <button id="load2"
                            class="py-1.5 px-4 rounded text-sm font-medium text-white
                                bg-[#0B5386]
                                shadow-sm shadow-[#0B5386]/30
                                hover:bg-[#094568]
                                hover:shadow-md hover:shadow-[#0B5386]/40
                                transition-all duration-200 ease-in-out
                                ">
                            Reset Password
                        </button>
                    </div>

                
                    <label class="block mb-4">
                        <span class="text-gray-700 font-sm form-label">For any query contact <a href="mailto: smadmin@presolv360.com">smadmin@presolv360.com</a></span>
                    </label>
            </div>
        </div>
    </div>

        <!-- ============================= -->
    <!--     FORGOT Username MODAL     -->
    <!-- ============================= -->
    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content p-6 rounded-xl shadow-lg">

                <div class="flex justify-between items-center mb-2">
                    <h5 class="text-xl font-semibold text-[#0B5386]">Forgot Username</h5>
                    <button type="button" class="close text-xl" data-dismiss="modal">&times;</button>
                </div>
                    <label class="block mb-2">
                        <span class="text-gray-700 font-medium form-label">@lang('site.Enter Email')</span>
                        <input type="email" name="forgot_username_reset" id="forgot_username_reset"
                               class="form-input mt-1 w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-[#0B5386] fgmobile"
                               placeholder="Email" required>

                        <span id="forgotUsernamMessage" class="block mt-1 text-xs" style="color:red;"></span>
                    </label>

                    <div class="flex">
                        <button id="load3"
                            class="py-1.5 px-4 rounded text-sm font-medium text-white
                                bg-[#0B5386]
                                shadow-sm shadow-[#0B5386]/30
                                hover:bg-[#094568]
                                hover:shadow-md hover:shadow-[#0B5386]/40
                                transition-all duration-200 ease-in-out
                                " data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">Reset Username</button>
                        </button>
                    </div>
                
                    <label class="block mb-4">
                        <span class="text-gray-700 font-sm form-label">For any query contact <a href="mailto:info@presolve360.com">info@presolv360.com</a></span>
                    </label>
            </div>
        </div>
    </div>

  <!-- Footer -->
  <footer class="absolute bottom-4 left-4 text-gray-600 text-sm">
    Presolv360 © <?=date('Y');?> All rights reserved.
  </footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="{{url('/assert/')}}/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.js"></script>
    <script src="{{url('/assert/')}}/js/brand-slider.js"></script>
    <script src="{{url('/assert/')}}/js/main.js"></script>
    <script src="{{url('/assert/')}}/js/site.js"></script>
    <script src="{{url('/assert/')}}/js/slick.js"></script>
    <script src="{{url('/assert/')}}/js/sweetalert2.js"></script>
    <script src="{{url('/assert/')}}/js/tailwind.js"></script>

    <script type="text/javascript">

        $(window).load(function() {

                    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".loader").fadeOut("slow");

        var hash = window.location.hash;

        })
    </script>

    <!-- Password Show/Hide Script -->
    <script>

        var DOMAIN = "{{url('/')}}";

        function togglePassword() {

            const password = document.getElementById("exampleInputPassword1");
            const eyeIcon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text";
                eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                    a10.056 10.056 0 012.263-3.592M9.88 9.88a3 3 0 
                    104.243 4.243M3 3l18 18" />
                `;
            } else {
                password.type = "password";
                eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 
                    8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7
                    -4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        $('#load2').on('click', function() {

            var str=document.getElementById('email_pass_reset').value;

            if(str!=='') {
                var $this = $(this);
                //$this.button('loading');
                $.ajax({

                url:DOMAIN+'/forgotpassword',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },
                data:{'username':str},
                success:function(d){

                    d=JSON.parse(d);
                    if(d.response=='success'){
                    swal("Success!", "Your Password Has Been Updated. Please Check Your Registered Email Id!", "success");
                }else{

                    swal("Failed!", "Wrong username entered!", "warning");
                }
                },
                error:function(e){

                    console.log(e);
                    swal("Failed!", "Wrong username entered!", "warning");
                }
                });

            }
            else
            {
                $this.button('reset');
                $('#myModal').modal('hide');

                swal("Failed!", "Please enter an Username!", "warning");
            }
        });

        $('#load3').on('click', function() {

            var str=document.getElementById('forgot_username_reset').value;

            if(str!=='') {

                var $this = $(this);
                $this.button('loading');

                $.ajax({

                url:DOMAIN+'/forgotusername',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{'email':str},
                success:function(d){
                    d=JSON.parse(d);
                    if(d.response=='success'){
                    swal("Success!", "Your Username Has Been Sent To Your Registered Email Id. Please Check Your Registered Email Id!", "success");
                    } else{
                    swal("Failed!", "Wrong email entered!", "warning");
                    }

                },
                error:function(e){

                    console.log(e);
                    swal("Failed!", "Wrong email entered!", "warning");
                }


                });

            }
            else
            {
                $this.button('reset');
                $('#myModal2').modal('hide');
                swal("Failed!", "Please enter an Email Id!", "warning");
            }

        });

    </script>


    @section('extra-js')
    @if(session()->has('warning'))
        <script>
            swal("Warning!", "{{ session()->get('warning') }}", "warning");
        </script>
    @endif

</body>
</html>
