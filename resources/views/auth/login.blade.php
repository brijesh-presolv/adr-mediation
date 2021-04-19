@extends('layouts.home')
@section('title', 'Login')

@section('content')

<style type="text/css">
    input::placeholder{
        font-size: 14px;
    }

    .floatdiv{
        position: absolute;
        right: 4%;
        top:22%;
        max-width: 450px;
        box-shadow: none;
        z-index: 1;
        background: #f3f3f3;
        border-radius: 5px;
    }

    .rdiv{
        padding-right:0 !important;
    padding-left: 17px;
    }
    .rdivmain{
        margin-top: 10px;
    }

    .page-banner{
        min-height: 571px;
    }

    .dwidth{
        width: 50%!important;
    }

    .dwidth2{
        width: 49%!important;
    }

    @media(min-width: 1700px){
        .floatdiv {
            right: 13%;
        }
    }


    @media(max-width: 768px){

        #email1{
            margin-bottom: 10px;
        }

        .page-banner{
            min-height: 300px!important;
        }



        .loginbtn {
    max-width: 100%;
}


.crbgtext {
    right: 32%;
    top: 30%;
}

        
        .floatdiv{
        position: relative;
        max-width: 100%;
            margin: 10px;
    background: #ffff;
    }

    .dwidth{
        width: 100%;
    }

    .dwidth2{
        width: 100%;
    }

    .btnmobiles{
        display: none;
    }
}


@media(max-width: 400px){

.page-banner {
    min-height: 200px!important;
}

.page-banner .crbgtextlogin {
    right: 24%;
    top: 12%;
}

.dwidth2 {
    width: 92%!important;
}
.dwidth {
    width: 92%!important;
}

.mobtns{
        text-align: left;
}

.mobtns .btn{
    margin-left: 0px!important;
}

#name{
    margin-bottom: 15px;
}

#mobile{
    margin-bottom: 15px;
}

.loginpage .form-horizontal .form-group {
    margin-bottom: 7px;
}
#organization, #username1, #password{
    margin-bottom: 5px;
}


}

@media(max-width: 360px){

   .Createac .btn-warning {
    width: 100% !important;
    margin-bottom: 10px;
}
}

.loginpage{
    padding-top: 150px;
    padding-bottom: 150px;
}

.loginpage{
    background: whitesmoke;
    
}
</style>


 <!-- <ol class="breadcrumb  bcrm" style="">
    
   <div class="container">
                        <li style="
    float: left;
    margin-right: 10px;
"><a href="https://presolv360.com/">Home /</a></li> <li class="active">Login/ Create Account</li>
                    </div></ol> -->


    <div id="aboutone-section" class="aboutone-section container-fluid no-padding blog-page-content loginpage">
        <!-- Container -->
        <div class="container ">

                
                    <div class="row">

                        <div class="col-md-7 mx-auto" >

                            <div class="card bxshadow">
                    <div class="card-body">
                            <!-- Nav tabs -->

                                                        <ul class="nav nav-tabs loginmobile">
                                <li class=""><a href="{{route('login')}}" class="active">Login</a></li>
                                <li class=""><a href="{{route('register')}}"  class="">Create Account</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content ">
                                <div class="tab-pane active" id="Login">
                                <br>
                                <form role="form" class="form-horizontal loginformsection" method="POST" action="login">
                                        @csrf
                                        <div class="form-group formmobile row">
                                            <!-- <label for="email" class="col-md-1 control-label" style="margin-right: 20px;">
                                                Username</label> -->
                                            <div class="col-sm-12">
                                                <label for="email" class="control-label" style="margin-right: 20px;">Email
                                                </label>
                                                <input type="email" class="form-control txtmobile" id="email1" placeholder="Email" name="email" value="prashant.blokess@gmail.com"required/>
                                            </div>
                                        </div>
                                        <div class="form-group formmobile row">
                                            <!-- <label for="exampleInputPassword1" class="col-md-1 control-label" style="margin-right: 20px;">
                                                Password</label> -->
                                            <div class="col-sm-12">
                                                <label for="exampleInputPassword1" class="control-label" style="margin-right: 20px;">
                                                Password</label>
                                                <input type="password" class="form-control txtmobile mb-2" id="exampleInputPassword1" placeholder="Password" name="password" value="User@123" required/>
                                            </div>
                                        </div>
                                    
                                        <div class="row mobtns">
                                            <div class="col-md-12" style="display:inline-block; text-align: center;">
                                                <button type="submit" class="btn btn-warning btn-lg btnmobile mt-2 dwidth" style="background-color: #FFA600;border: 1px;">
                                                    Submit</button>
                                                <button type="button" class="btn btn-warning btn-lg btnmobile mt-2 dwidth2" style="background-color:#0B5386;border: 1px;" data-toggle="modal" data-target="#myModal">Forgot Password?</button>
                                                 <button type="button" class="btn btn-warning btn-lg btnmobile mt-2 dwidth" style="background-color: #0B5386;border: 1px;width:188px;"data-toggle="modal" data-target="#myModal2">Forgot Username?</button>
                                            </div>
                                            <div class="col-xs-12 d-sm-none d-md-none d-lg-none btnmobile btnmobiles">
                                             <button type="submit" class="btn btn-warning btn-block btnmobile" style="background-color: #FFA600;border: 1px;width:100%;">
                                                    Submit</button>
                                                    <button type="button" class="btn btn-warning btn-block btnmobile" style="background-color:#0B5386;width:100%; " data-toggle="modal" data-target="#myModal">Forgot Password?</button>
                                                    <button type="button" class="btn btn-warning btn-block btnmobile mt-2" style="background-color: #0B5386;border: 1px;width:100%;"    data-toggle="modal" data-target="#myModal2">Forgot Username?</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <div class="tab-pane " id="Registration">
                                <br>
                                    <form role="form" class="form-horizontal" method="POST" action="{{ route('register') }}">
                                                 @csrf                              <div class="form-group row">
                                            <!-- <label for="email" class="col-sm-2 control-label pdr20 ">
                                               First Name*</label> -->
                                            <div class="col-sm-6 ml25 pr5">
                                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="First Name">

                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                               
                                            </div>
                                            <div class="col-sm-6 ml25 pl5">
                                                
                                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus placeholder="Last name">

                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                                   
                                            </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <!-- <label for="email" class="col-sm-2 control-label pdr20 ">
                                               Last Name*</label> -->
                                            
                                        </div>
                                        <div class="form-group row">
                                            <!-- <label for="organization" class="col-sm-2  control-label pdr20 ">
                                                Organization</label> -->

                                             <div class="col-sm-6 ml25 pr5">
                                                 <input id="mobile_number" type="text" class="form-control @error('mobile_number') is-invalid @enderror" name="mobile_number" value="{{ old('mobile_number') }}" required autocomplete="mobile_number" placeholder="mobile" autofocus>

                                @error('mobile_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                            
                                             <div class="col-sm-6 ml25 pl5">
                                                 <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <!-- <label for="email" class="col-sm-2  control-label pdr20">
                                                Email*</label> -->
                                           
                                        </div>
                                        <div class="form-group row">
                                            <!-- <label for="mobile" class="col-sm-3  control-label pdr20">
                                                Organization*</label> -->
                                            <div class="col-sm-12 ml25">
                                                <input id="organization" type="text" class="form-control @error('organization') is-invalid @enderror" name="organization" value="{{ old('organization') }}" required autocomplete="organization" autofocus placeholder="Organization">

                                @error('organization')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                           
                                        </div>
                                    
                                        <div class="form-group row">
                                            <!-- <label for="username" class="col-sm-3  control-label pdr20">
                                                Username*</label> -->
                                            <div class="col-sm-12 ml25">
                                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">

                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>


                                        </div>
                                        <div class="form-group row">
                                             <!-- <label for="password" class="col-sm-3 control-label pdr20">
                                                Password*</label> -->
                                            <div class="col-sm-12 ml25">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                             <!-- <label for="password" class="col-sm-3 control-label pdr20">
                                                Password*</label> -->
                                            <div class="col-sm-12 ml25">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row rdivmain">
                                            <label for="password" class="col-sm-3 control-label pdr20 rdiv">
                                                Register As*</label>
                                            <div class="col-sm-9 ml25">
                                                <label class="radio-inline" style="margin-right: 10px;">
                                                     <input type="radio" name="actype" value="1" required="" style="
    margin-right: 5px;
">User
                                                </label>
                                                <label class="radio-inline">
                                                      <input type="radio" name="actype" value="2" required="">Mediator
                                                </label>
                                            </div>
                                        </div>




                                                                                <div class="row" id="div3" >
                                            <div class="col-sm-12 ml3 Createac">

                                                <div class="g-recaptcha" data-sitekey="6LcOBP8UAAAAAJGLLpiJyEKoCnr-dpD9ikWcxzRl" data-badge="inline" data-size="invisible" data-callback="setResponse"></div>
    
                                         <input type="hidden" id="captcha-response" name="captcha-response" />
                                         
                                                <button type="submit" class="btn btn-warning btn-lg" style="background-color:#FFA600;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait.."> Create Account </button>

                                                <button type="reset" style="background-color:#0B5386;"  class="btn btn-warning btn-lg">
                                                    Reset</button>

                                            </div>

                                        </div>
                                        <div class="alert alert-danger" id="error" style="display:none;">

                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                            <!--<div id="OR" class="d-none">
                                OR</div>-->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- model for resetpassword -->
                                <div class="modal fade" id="myModal" role="dialog">
                                    <div class="modal-dialog modal-xs" style="width: 360px; margin-top: 80px;" >
                                        <div class="modal-content frgpmobile">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Forgot Password ?</h4>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group ">
                                                    <label for="email" class="d-sm-none ">Enter Username</label>
                                                    <input type="text" class="form-control fgmobile" id="username_reset" name="username_reset" placeholder="Username" >
                                                </div>
                                                <button type="button" class="btn btn-primary btn-lg" id="load2" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">Reset Password</button>
                                                <br/>

                                                <div>For any query contact <a href="mailto:info@presolve360.com">info@presolv360.com</a></div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                
                                <!--End model for resetpassword -->
                                
                                
                                
                                <div class="modal fade" id="myModal2" role="dialog">
                                    <div class="modal-dialog modal-xs" style="width: 360px;margin-top: 80px;" style="height:60%;">
                                        <div class="modal-content frgpmobile">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Forgot Username ?</h4>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label for="email" class="d-sm-none ">Enter Email</label>
                                                    <input type="text" class="form-control fgmobile" id="forgot_username_reset" name="forgot_username_reset" placeholder="Email" >
                                                </div>
                                                <button type="button" class="btn btn-primary btn-lg" id="load3" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">Reset Username</button>
                                                <br/>

                                                <div>For any query contact <a href="mailto:info@presolve360.com">info@presolv360.com</a></div>
                                            </div>

                                        </div>

                                    </div>
                                </div>


   <style type="text/css">
       .btn-warning {
             width: 182px;
             box-shadow: none;
             border: none;

        }
        .grecaptcha-badge { 
    visibility: hidden;
    height: 0px!important;
}

    .loginformsection .btnsection .btn-group-lg > .btn, .btn-lg {
        font-size: 17px;
    }
    .pdr20{
        padding-right: 20px;
    }
    .ml3{
        margin-left: 3px;
    }

    .close{
        position: absolute;
        right: 5%;
    }

    .rform .form-control{
        font-size: 13px;
    }

   

    .rform input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  font-size: 11px;
}
.rform input::-moz-placeholder { /* Firefox 19+ */
  font-size: 11px;
}
.rform input::-ms-input-placeholder { /* IE 10+ */
  font-size: 11px;
}

@media only screen and (min-width: 768px) {

     .rform .pl5{
        padding-left: 5px;
    }

    .rform .pr5{
        padding-right: 5px;
    }


}


    @media only screen and (max-width: 600px) {
        
        .ml25{
            margin-left: 25px;
        }

        .ml3 {
    margin-left: 25px;
}
    }

    
    
   </style>

<!--    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>

        <script type="text/javascript">
            var onloadCallback = function() {
    grecaptcha.execute();
};

function setResponse(response) { 
    document.getElementById('captcha-response').value = response; 
}
        </script> -->
        
@section('content')