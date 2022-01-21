<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name', 'Mediation') }} | @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Responsive bootstrap 4 admin template" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        {{-- <link rel="shortcut icon" href="{{url('')}}/assets/images/favicon.ico"> --}}
        <link rel="icon" type="image/x-icon" href="{{url('/assert/')}}/img/icon.png" />

        <!-- @yield('head') -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App css -->
        <link href="{{url('assets/')}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
        <link href="{{url('assets/')}}/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('assets/')}}/css/app.min.css" rel="stylesheet" type="text/css" id="app-stylesheet" />

        <style>
            .private_total {
                position: absolute;
                top: -0.8em; 
                left: 4.5em
            }
            .private_unseen {
                position: absolute;
                top: -0.8em; 
                left: 3em
            }
            .share_total {
                position: absolute;
                top: -0.8em; 
                left: 10em
            }
            .share_unseen {
                position: absolute;
                top: -0.8em; 
                left: 8.5em
            }
        </style>
        <!-- Dynamic pages css comes -->
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
                            <!-- <img src="{{ url('/') }}/assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle"> -->
                            <span class="d-none d-sm-inline-block ml-1"><?= Auth::user()->first_name ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="{{url('mediator/profile')}}" class="dropdown-item notify-item">
                                <i class="mdi mdi-account-outline"></i>
                                <span>Profile</span>
                            </a>


                            <div class="dropdown-divider"></div>

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
                    <a href="{{ url('/') }}" class="logo text-center">
                        <span class="logo-lg">
                            <!-- <img src="{{ url('/') }}/assets/images/logo-light.png" alt="" height="18"> -->
                            <span class="logo-lg-text-light">Mediation</span>
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
            @include('mediator.layouts.sidebar')
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
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
                                    <h4 class="page-title">@yield('pageTitleOnDashboard')</h4>
                                    {{-- @yield('pageTitleOnDashboard') --}}
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
        <!-- <div class="rightbar-overlay"></div> -->

        <!-- Vendor js -->
        <script src="{{url('assets/')}}/js/vendor.min.js"></script>

        <script src="{{url('assets/')}}/libs/morris-js/morris.min.js"></script>
        <script src="{{url('assets/')}}/libs/raphael/raphael.min.js"></script>

        <script src="{{url('assets/')}}/js/pages/dashboard.init.js"></script>
        <script src="{{url('assets/')}}/form-validator/jquery.form-validator.js"></script>
        <!-- App js -->
        <script src="{{url('assets/')}}/js/app.min.js"></script>

        <script>
                                   $.ajaxSetup({
                                       headers: {
                                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                       }
                                   });
        </script>
        <!-- Dynamic pages js comes and other files-->

        @yield('footer')
    </body>

</html>
