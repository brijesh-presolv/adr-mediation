<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Mediation') }}| Login</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{url('/assert/admin/')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('/assert/admin/')}}/dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{url('/assert/admin/')}}/index2.html"><b>Mediation</b> Admin</a>
            </div>
            <!-- /.login-logo -->
            <!-- <div class="card"> -->
                  <div class="card">

                        <div class="text-center btn-dark">
                            <div class="mt-2 mb-2">
                                <a href="index.html" class="text-yellow">
                                   <b>ADMIN-LOGIN</b>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">

                        <form method="post" action="{{ route('login') }}">
                        @csrf

                                <div class="form-group">
                                    <input class="form-control @error('email') is-invalid @enderror" type="email" id="email" placeholder="Username" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>

                                <div class="form-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox checkbox-success">
                                        <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked="{{ old('remember') ? 'checked' : '' }}">
                                        <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                    </div>
                                </div>

                                <div class="form-group text-center mt-4 pt-0">
                                    <div class="col-sm-12">
                                        <a href="page-recoverpw.html" class="text-muted"><i class="fa fa-lock mr-1"></i> Forgot your password?</a>
                                    </div>
                                </div>

                                <div class="form-group account-btn text-center mt-0">
                                    <div class="col-12">
                                        <button class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>

                            </form>

                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->

                <!-- /.login-card-body -->
            </div>
        <!-- </div> -->
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="{{url('/assert/admin/')}}/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="{{url('/assert/admin/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="{{url('/assert/admin/')}}/dist/js/adminlte.min.js"></script>
    </body>
</html>
