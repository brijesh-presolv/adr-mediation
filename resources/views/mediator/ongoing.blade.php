<?php
use App\Models\InvoledUser;
?>

@extends('mediator.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Ongoing </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="header-title"><b>Ongoing</b></h4>
            <table  id="datatable" id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Date</th>
                        <th>Party Details</th>
                        <th>Case Detail</th>
                        <th>Commets</th>
                        <th>Session</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sno = 1; ?>
                    @foreach($ongoingData as $data)  
                    <tr>
                        <td>{{ $sno }}</td>
                        <td>M00000{{ $data->mediation_case_id  }}</td>
                        <td>{{ date('d-m-Y', strtotime($data->created_at))}}</td>
                        
                        <td><?php 
                            $invuser=InvoledUser::select('name','isOnboarded')->where(['userPlanid'=>$data->userid])->get();

                            foreach ($invuser as $key => $value) {

                            if($value->isOnboarded==1){
                                echo '<span class="text-success">'.$value->name.'</span></br>';
                            } else{
                                echo '<span class="text-danger">'.$value->name.'</span></br>';
                            }
                            
                        }
                         ?>
                         </td>
                        <td>
                            <button class="btn btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment">Case Detail</button>
                        </td>
                        <td>
                            <button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment">Private</button>
                            <button class="btn  btn-sm  btn-success label label-success " data-toggle="modal" data-target="#myModalcomment">Shared</button>
                        </td>
                        <td>
                            <a href="#addSession-modal" class="btn-sm btn-primary waves-effect waves-light" data-animation="swell" data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a">Add Session</a>
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm">Agreement</a>
                            <br>
                        </td>
                        <!-- <td>MD000200</td>
                        <td><button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',1,'425')">Case details</td>
                        <td>Party 1 <br>
                            Party 2 <br>
                            Party 3 <br>
                        </td>
                        <td>
                            <button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',1,'425')">Private</button>
                            <button class="btn  btn-sm  btn-success label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',2,'a')">Shared</button>
                        </td>
                        <td> --><!-- <button class="btn btn-inline btn-primary label label-success arbacceptbtn1" data-toggle="modal" data-target="#myModal53" data-cid="1243" >Accept</button> -->

                      <!--   <a href="/arbitrator/disclosure?id=1243" class="btn btn-primary btn-sm">Agreement</a>
                        <br></td> -->
                    </tr>
                     <?php $sno++ ?>   @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

   <!-- Modal Start -->
    <div id="addSession-modal" class="modal-demo">

        <button type="button" class="close" onclick="Custombox.modal.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <form id="addSessionForm">
            <h4 class="custom-modal-title bg-dark">Add Session</h4>
            <div class="custom-modal-text ">
                
                <span>Session Date :</span>
                <input type="text" id="sessionDate" class="form-control" name="sessionDate" placeholder="Select session date">

                <span>Session Time :</span>
                <input type="time" id="sessionTime" class="form-control" name="sessionTime" placeholder="Select session Time">

                <span>Zoom Id :</span>
                <input type="text" id="zoomId" class="form-control" name="zoomId" placeholder="Paste meeting Id Or Zoom Id">

                <span>Note :</span>
                <textarea class="form-control" id="note" name="note" placeholder="Add aditional notes"></textarea>
                
                <div class="text-center">    
                    <input type="submit" name="addSession" class="btn-sm btn-primary mt-3">
                </div>
            </div>
        </form>
    </div>

   <!-- Modal End -->

@endsection

 <!-- Table datatable css -->
@section('head')
  
    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/libs/custombox/custombox.min.css" rel="stylesheet" type="text/css">
    
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


@endsection


@section('footer')
 <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

 <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>

    <script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>

      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(function() {
    $( "#sessionDate").datepicker({ minDate: 0});
  });
</script>

 <script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: '{{ route("mediator.addSession") }}',
            data: $('form').serialize(),
            success: function () {
              alert('form was submitted');
            }
          });

        });

      });
    </script>


@endsection























