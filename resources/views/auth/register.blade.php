<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Presolv360</title>
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
    <script src="https://cdn.tailwindcss.com"></script>

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
        }

        .form-label {
            font-size: 12px !important;

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
</head>

<body class="min-h-screen flex flex-col justify-center relative bg-gradient-to-br from-[#eef3f7] via-[#f7f9fb] to-[#ffffff]">

    <!-- Logo -->
    <div class="logo-wrapper absolute top-4 left-4 md:top-6 md:left-12 z-50">
        <a href="https://presolv360.com/">
            <img src="{{url('/assert/')}}/img/logo.png" class="h-10" alt="Site Logo">
        </a>
    </div>

    <!-- Register Box -->
    <div class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="form-box w-full max-w-lg border border-[#c8d6e2] rounded-xl p-6">

            <!-- Header -->
            <div class="flex flex-col items-center mb-4">
                <h4 class="text-1xl font-semibold text-[#0B5386] text-center form-title">Create your account</h4>
                <p class="text-gray-600 text-sm text-center">Fill the details to register.</p>
            </div>

            <form role="form" method="POST" action="{{ route('register') }}">
                @csrf

                <!-- First Name + Last Name (same row) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                    <div>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" minlength="2" placeholder="@lang('site.First Name')*"
                            class="form-input w-full px-4 py-2 border border-gray-300 bg-white rounded-md focus:ring-2 focus:ring-[#0B5386] @error('first_name') border-red-500 @enderror rfname"
                            minlength="2" maxlength="70" pattern="[A-Za-z]+" title="Name must contain only alphabets without space"  required autocomplete="last_name" autofocus />
                            <span id="nameMessage" style="font-size: 12px;"></span>
                            @error('first_name')
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                            @enderror
                    </div>
                    <div>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" minlength="2" placeholder="@lang('site.Last name')*"
                            class="form-input w-full px-4 py-2 border border-gray-300 bg-white rounded-md focus:ring-2 focus:ring-[#0B5386] rlname"
                            minlength="2" maxlength="70" pattern="[A-Za-z]+" title="Last Name must contain only alphabets without space" required autocomplete="last_name" autofocus/>
                            <span id="lnameMessage" style="font-size: 12px;"></span>

                        @error('last_name')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Mobile + Email (same row) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                    <div>
                        <input type="text" id="mobile_number" name="mobile_number" minlength="10" maxlength="10" value="{{ old('mobile_number') }}"
                            pattern="[1-9]{1}[0-9]{9}" placeholder="@lang('site.Mobile')"
                            class="form-input w-full px-4 py-2 border border-gray-300 bg-white rounded-md focus:ring-2 focus:ring-[#0B5386] @error('mobile_number') border-red-500 @enderror rmobile"
                            minlength="10" maxlength="10" pattern="[1-9]{1}[0-9]{9}" />
                        <span id="mobileMessage" style="font-size: 12px;"></span>
                        @error('mobile_number')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <input type="email" id="email" name="email" placeholder="Email*"
                            class=" form-input w-full px-4 py-2 border border-gray-300 bg-white rounded-md focus:ring-2 focus:ring-[#0B5386] @error('email') border-red-500 @enderror remail"
                            maxlength="80" required autocomplete="email" placeholder="@lang('site.email')*"/>
                        <span id="emailMessage" style="font-size: 12px;"></span>
                        @error('email')
                        <p class="text-red-600 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid gap-2 mb-1">
                    <small>Note: This field is only for mobile numbers registered in India.</small>
                </div>

                <!-- Organization -->
                <div class="mb-2">
                    <input type="text" name="organization" id="organization" placeholder="Organization" value="{{ old('organization') }}"
                        class="form-input w-full px-4 py-2 border border-gray-300 bg-white rounded-md focus:ring-2 focus:ring-[#0B5386] @error('organization') is-invalid @enderror rorg" minlength="2" maxlength="70" title="Please enter at least 2 characters, including letters, numbers, and special characters." required autocomplete="organization"  />
                    <span id="orgMessage" style="font-size: 12px;"></span>

                    @error('organization')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-2">
                    <input type="text" name="username" name="username" value="{{ old('username') }}" placeholder="Username*: Minimum 6 characters" 
                        class="form-input w-full px-4 py-2 border border-gray-300 bg-white rounded-md focus:ring-2 focus:ring-[#0B5386] @error('username') is-invalid @enderror runame"  minlength="6" maxlength="20" pattern="[A-Za-z0-9]+" title="Username must contain only alphanumeric characters"  required />
                        <span id="unameMessage" style="font-size: 12px;"></span>

                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                    <!-- Password -->
                    <div class="mb-2">
                        <!-- Input + Eye -->
                        <div class="relative">
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-input w-full px-4 py-2 pr-10 border border-gray-300 bg-white rounded-md 
                                    focus:ring-2 focus:ring-[#0B5386] @error('password') border-red-500 @enderror rpass"
                                placeholder="Password*" 
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" 
                                title="Password must contain at least 6 characters, including 1 uppercase letter, 1 lowercase letter, and 1 numerical digit" 
                                minlength="6" 
                                required
                            />

                            <!-- Eye Icon -->
                            <span 
                                class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500"
                                onclick="togglePassword('password','eye1')">
                                <svg id="eye1" xmlns="http://www.w3.org/2000/svg" fill="none"
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
                <!--Confirm Password -->
                <div class="mb-2">
                    <!-- Input + Eye -->
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password2" 
                            class="form-input w-full px-4 py-2 pr-10 border border-gray-300 bg-white rounded-md 
                                focus:ring-2 focus:ring-[#0B5386] @error('password') border-red-500 @enderror rcpass"
                        placeholder="@lang('site.Confirm password')*: Should be same as Password"
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" 
                        title="Confirm Password must contain at least 6 characters, including 1 uppercase letter, 1 lowercase letter, and 1 numerical digit"
                        data-validation="strength" 
                        data-validation-strength="2" 
                            required
                        />

                        <!-- Eye Icon -->
                        <span 
                            class="absolute inset-y-0 right-3 flex items-center cursor-pointer text-gray-500"
                            onclick="togglePassword('password2','eye2')">
                            <svg id="eye2" xmlns="http://www.w3.org/2000/svg" fill="none"
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

                <!-- Register As -->

                <div class="mb-2">
                    <div class="flex gap-4 items-center">
                        <label class="flex items-center gap-2">
                            <span class="text-gray-700 mb-1 block form-label">@lang('site.registeras')*</span>
                        </label>
                        <label class="flex items-center gap-2 form-label">
                            <input type="radio" name="actype" value="1" required class="accent-[#0B5386]"> @lang('site.User')
                        </label>
                        <label class="flex items-center gap-2 form-label">
                            <input type="radio" name="actype" value="2" required class="accent-[#0B5386]"> @lang('site.Mediator')
                        </label>
                    </div>
                    <span id="registerAsMessage" class="block mt-1 text-xs" style="color:red;"></span>
                </div>

                <!-- Terms -->
                <div class="mb-2">
                    <label class="flex items-start gap-2 text-gray-700 form-label">
                    <input type="checkbox" name="is_agree" class="mt-1 form-input" required>
                    <span class="">
                    I have read and agree to the
                        <a href="https://presolv360.com/terms-conditions" target="_blank" class="text-[#0B5386]">Terms & Conditions</a>,
                        <a href="https://presolv360.com/privacy-policy" target="_blank" class="text-[#0B5386]">Privacy Policy</a> and
                        <a href="https://presolv360.com/cookie-policy" target="_blank" class="text-[#0B5386]">Cookie Policy</a>.
                     </span>
                </div>

                <!-- GOOGLE reCAPTCHA INVISIBLE -->
                <div class="g-recaptcha"
                     data-sitekey="6LcOBP8UAAAAAJGLLpiJyEKoCnr-dpD9ikWcxzRl"
                     data-size="invisible"
                     data-callback="setResponse">
                </div>

                <input type="hidden" id="captcha-response" name="captcha-response" />

                <!-- Submit Buttons -->
                    <div class="flex justify-center">
                        <button 
                            class="py-1.5 px-4 rounded text-sm font-medium text-white
                                bg-[#0B5386]
                                shadow-sm shadow-[#0B5386]/30
                                hover:bg-[#094568]
                                hover:shadow-md hover:shadow-[#0B5386]/40
                                transition-all duration-200 ease-in-out
                                rsub">
                            Create Account
                        </button>
                    </div>

                    <p class="mt-6 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[#0B5386] font-medium hover:underline">Login here</a>
                    </p>

            </form>

        </div>
    </div>

  <footer class="absolute bottom-4 left-4 text-gray-600 text-sm">
    Presolv360 © <?=date('Y');?> All rights reserved.
  </footer>

   <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>

    <script>

        var onloadCallback = function() {
            grecaptcha.execute();  // auto-trigger reCAPTCHA
        };
        function setResponse(response) {
            document.getElementById('captcha-response').value = response;
        }
    </script>

        <script>

        function togglePassword(inputId, iconId) {

            const field = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (field.type === "password") {
                field.type = "text";
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7 
                        0.758-2.416 2.334-4.436 4.374-5.678M6.18 6.18A9.967 
                        9.967 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.05 
                        10.05 0 01-1.043 2.366M15 12a3 3 0 00-4.243-2.828M9 
                        9l6 6" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3l18 18" />
                `;
            } else {
                field.type = "password";
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 
                        0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 
                        7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

    </script>

</body>
</html>
