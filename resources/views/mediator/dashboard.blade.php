@extends('mediator.layouts.app')


    @section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard </a></li>
    <!-- end page title -->
    @endsection
@section('content')
<div class="row">
    <div class="col-sm-4">
      <span>Last Login: <?php session_start();  echo Session::get('last_login'); ?>  <br> </span>
      <br>
    </div>
</div>
 
<div class="row">
    <div class="col-12">
    <div class="card-box">
      <center><h1 class="box-title m-b-0" style="margin-bottom: 20px;margin-top: 5%;">Welcome to</h1>
          <h1 class="box-title m-b-0" style="margin-bottom: 5%;font-weight:800;font-size:85px;font-family: 'Poppins', sans-serif;"><span style="color: #075284;"></span><span style="margin-bottom: 20px;font-weight:800;font-size:70px;color: #075284;">Presolv</span><span style="color: #f6ac4c;">360</span></h1>
          <!-- <h3 class="box-title m-b-0" style="margin-bottom: 150px;font-weight:800;font-size:25px;"><span style="color: #727374;">RESOLVE &amp; EVOLVE</span></h3> -->
  <!--<img src="https://presolv360.com/public/images/logo12.png" alt=""> </center>-->
      </center>
    </div>
    </div>
</div>
<!-- end end -->




<!-------- Upcoming Session List ----------->
<div class="row">
    <div class="col-lg-12">
        <h4>Upcoming Sessions</h4>
            <div class="card-box table-responsive">
                <table id="upcoming" class="table table-striped table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <th>@lang('case.case_id') </th>
                                <th>Initiating Party(s)</th>
                                <th>Responding Party(s)</th>
                                <th>Mediator</th>
                                <th>Session Date & Time</th>
                                <th>Zoom Link</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                </table>
            </div>
    </div>
</div>
<!-------- Upcoming Session List ----------->
@endsection



@section('head')
    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    
@endsection


@section('footer')



    <!-- <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script> -->

    <!-- Datatables init -->
    <!-- <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

    <script type="text/javascript">
        $(document).ready(function(){
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
           // alert(csrf);
            getUpcoming(csrf);
            
        });

        function getUpcoming(csrf){
            $.ajax({
                type: 'post',
                url: '{{ route('mediator.case.getUpcomingSession') }}',
                data: {
                   '_token': csrf
                },
                beforeSend: function() {
                    $('#upcoming tbody').html('<tr>loading...</tr>');
                },
                success: function(data) {

                   
                    //var pdfButton = "";
                    if (data != "") {
                        
                        $('#upcoming tbody').html(data);
                        //$('#sessionShowBtn').html(pdfButton);
                    } else {
                        $('#upcoming tbody').html("No Session");
                        //$('#sessionShowBtn').html(pdfButton);

                    }
                }

            });
        }
    </script>
@endsection