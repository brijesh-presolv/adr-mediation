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
                                <li class=""><a href="{{route('login')}}" class="active">@lang('site.login')</a></li>
                                <li class=""><a href="{{route('register')}}"  class="">@lang('site.createaccount')</a></li>
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
                                                <label for="email" class="control-label" style="margin-right: 20px;">@lang('site.email')
                                                </label>
                                                <input type="email" class="form-control txtmobile @error('email') is-invalid @enderror" id="email1" placeholder="Email" name="email" value=""required/>
                                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group formmobile row">
                                            <!-- <label for="exampleInputPassword1" class="col-md-1 control-label" style="margin-right: 20px;">
                                                Password</label> -->
                                            <div class="col-sm-12">
                                                <label for="exampleInputPassword1" class="control-label" style="margin-right: 20px;">
                                                @lang('site.password')</label>
                                                <input type="password" class="form-control txtmobile mb-2 @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Password" name="password" value="" required/>
                                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                            </div>
                                        </div>
                                    
                                        <div class="row mobtns">
                                            <div class="col-md-12" style="display:inline-block; text-align: center;">
                                                <button type="submit" class="btn btn-warning btn-lg btnmobile mt-2 dwidth" style="background-color: #FFA600;border: 1px;">
                                                    Submit</button>
                                            </div>

                                            <div class="col-md-12">

                                                <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <!--  <center><p>OR</p></center> -->
                                                </div>
                                                   
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-warning btn-block " style="background-color:#0B5386;width:80%; " data-toggle="modal" data-target="#myModal">@lang('site.forgotpassword')</button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-warning btn-block" style="background-color: #0B5386;border: 1px;width:80%;"    data-toggle="modal" data-target="#myModal2">@lang('site.forgotusername')</button>
                                                    </div>
                                                </div>
                                                 

                                            </div>
                       
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
                                    <div class="modal-dialog modal-xs"  >
                                        <div class="modal-content frgpmobile">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">@lang('site.forgotpassword')</h4>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group ">
                                                    <label for="email" class="d-sm-none ">@lang('site.Enter Username')</label>
                                                    <input type="text" class="form-control fgmobile" id="username_reset" name="username_reset" placeholder="Username" >
                                                </div>
                                                <button type="button" class="btn btn-primary btn-lg" id="load2" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">@lang('site.Reset Password')</button>
                                                <br/>

                                                <div>For any query contact <a href="mailto:info@presolv360.com">info@presolv360.com</a></div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                                
                                <!--End model for resetpassword -->
                                
                                
                                
                                <div class="modal fade" id="myModal2" role="dialog">
                                    <div class="modal-dialog modal-xs">
                                        <div class="modal-content frgpmobile">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">Forgot Username</h4>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label for="email" class="d-sm-none ">@lang('site.Enter Email')</label>
                                                    <input type="text" class="form-control fgmobile" id="forgot_username_reset" name="forgot_username_reset" placeholder="Email" >
                                                </div>
                                                <button type="button" class="btn btn-primary btn-lg" id="load3" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait..">@lang('site.Reset Username')</button>
                                                <br/>

                                                <div>For any query contact <a href="mailto:info@presolv360.com">info@presolv360.com</a></div>
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
        
@endsection('content')

@section('extra-js')

    @if(session()->has('warning'))
        <script>


            
            swal("Warning!", "{{ session()->get('warning') }}", "warning");
        </script>
    @endif
@endsection