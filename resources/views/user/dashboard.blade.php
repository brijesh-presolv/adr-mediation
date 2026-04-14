@extends('user.layouts.app')


    @section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard </a></li>
    <!-- end page title -->
    @endsection

<style>
    .button-custom {
        background: #ffa600;
    /* background: #b3b3fb; */
    border-radius: 18px;
    color: #fff;
    padding: 8px 28px;
    }
</style>
@section('content')
<div class="row">
    <div class="col-sm-4">
      <span>Last Login: <?php session_start();  echo Session::get('last_login'); ?>  <br> </span>
      <br>
    </div>
</div>
    
<div class="row">
    <div class="col-12">
    <center><h1 class="box-title m-b-0" style="margin-bottom: 20px;margin-top: 5%;">Welcome to</h1>
        <h1 class="box-title m-b-0" style="margin-bottom: -12px;font-weight:800;font-size:85px;font-family: 'Poppins', sans-serif;"><span style="color: #075284;"></span><span style="margin-bottom: 20px;font-weight:800;font-size:70px;color: #075284;">Presolv</span><span style="color: #f6ac4c;">360</span></h1>
        <!-- <h3 class="box-title m-b-0" style="margin-bottom: 150px;font-weight:800;font-size:25px;"><span style="color: #727374;">RESOLVE &amp; EVOLVE</span></h3> -->
 <!--<img src="https://presolv360.com/public/images/logo12.png" alt=""> </center>-->

 <br/>
<p>Register an existing dispute and opt for e-arbitration or e-mediation</p>

<?php
//$server_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
?>
 <a class="button-custom" href="<?php echo url('/');?>/#howitwork">Get Started</a>

</center>
</div>
</div>
<!-- end end -->
@endsection