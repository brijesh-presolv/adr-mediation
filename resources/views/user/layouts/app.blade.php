<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Mediacje') }} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    {{-- <link rel="shortcut icon" href="{{url('')}}/assets/images/favicon.ico"> --}}
    <link rel="icon" type="image/x-icon" href="{{url('/assert/')}}/img/icon.png" />

    <!-- App css -->
    <link href="{{url('assets/')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{url('assets/')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets/')}}/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
    <link href="{{url('assets/')}}/css/custom.css" rel="stylesheet" type="text/css" id="app-stylesheet" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <!-- Dynamic pages css comes -->


    <style>
        .share_total {
                position: absolute;
                top: -0.8em; 
                left: 4em
            }
            .share_unseen {
                position: absolute;
                top: -0.8em; 
                left: 2.5em
            }
    </style>

    @yield('head')
</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-right mb-0">

                <li class="dropdown notification-list">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="d-none d-sm-inline-block ml-1"><?= Auth::user()->first_name.' '.Auth::user()->last_name?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">


                        <div class="dropdown-divider"></div>

                        <!-- item-->
                       <!--  <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="mdi mdi-logout-variant"></i>
                            <span>Logout</span>
                        </a>
 -->
                        <a class="dropdown-item notify-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-logout-variant" title="Logout"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>

            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="{{ url('/') }}" class="logo text-center" style="background-color:#fdfdfd ">
                    <span class="logo-lg">
                        <!-- <img src="{{ url('/') }}/assets/images/logo-light.png" alt="" height="18"> -->
                        <span class="logo-lg-text-light" style="color: #575a65 ">Mediation</span>
                    </span>
                    <span class="logo-sm">
                        <!-- <span class="logo-sm-text-dark">Z</span> -->
                        <img src="{{ url('/') }}/assets/images/logo-sm.png" alt="" height="24">
                    </span>
                </a>
            </div>
            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>
        </div>
        <!-- end Topbar --> 
        
        <!-- ========== Left Sidebar Start ========== -->
         @include('user.layouts.sidebar')
            <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page" >
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">
                       <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                     @yield('breadcrumb')
                                </ol>
                            </div>
                            <h4 class="page-title">@yield('page_title')</h4>

                        </div>
                    </div>
                </div>
                <!-- end page title -->

                     @yield('content')


                    <!-- end start -->
                        <!-- ...... -->
                    <!-- end row -->

                </div>
                <!-- end container-fluid -->

            </div>
            <!-- end content -->

            

            <!-- Footer Start -->
            {{-- <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            2020 - 2021 &copy; by <a href="">{{ config('app.name', 'Mediation') }}</a>
                        </div>
                    </div>
                </div>
            </footer> --}}
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- <a href="javascript:void(0);" class="right-bar-toggle demos-show-btn">
        <i class="mdi mdi-settings-outline mdi-spin"></i> &nbsp;Choose Demos
    </a> -->

    <!-- Vendor js -->
    <script src="{{url('assets/')}}/js/vendor.min.js"></script>

    <script src="{{url('assets/')}}/libs/morris-js/morris.min.js"></script>
    <script src="{{url('assets/')}}/libs/raphael/raphael.min.js"></script>

    <script src="{{url('assets/')}}/js/pages/dashboard.init.js"></script>
    <script src="{{url('assets/')}}/form-validator/jquery.form-validator.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <!-- App js -->
    <script src="{{url('assets/')}}/js/app.min.js"></script>


    <!-- Dynamic pages js comes and other files-->

    @yield('footer')

    <script type="text/javascript">
    

    $(document).ready(function(){


        var rowid=1;

        $(document).on('click','#addmore',function(e){

            rowid++;

            e.preventDefault();

            var resp=$('.respondent').clone();
            resp.prepend('<hr><p>#Respondent '+rowid+'</p>');
            resp.removeClass('respondent');
            resp.attr('id','rowid'+rowid);

            resp.find('.form-control').val('');

            $('.respondents').append(resp);

        });

        $(document).on('click','#removeresp',function(e){

            e.preventDefault();

            $('#rowid'+rowid).remove();

            rowid--;

        });
    });
</script>
</body>

</html>