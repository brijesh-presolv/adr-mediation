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
                    <input type="hidden" name="" id="createdBy" value="{{ $data->mediator_id }}">

                    <tr>
                        <td>{{ $sno }}</td>
                        <td>M<span id="caseId">{{ sprintf("%06d",$data->mediation_case_id)  }}</span></td>
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
                            <a href="#addSession-modal" id="tooltip-animation" title="Add new Session!" class="btn btn-warning waves-effect btn-sm" data-animation="swell" data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a" onclick="addReqData()" ><span class="mdi mdi-pencil-plus"></span></a>
                            <!-- <a href="#viewSession-modal" class="btn-sm btn-success waves-effect waves-light" data-animation="swell" data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a" onclick="getSessionData()" >View Session</a> -->
                            <a href="#" id="tooltip-animation" title="View added Sessions!" class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target=".bs-example-modal-lg" onclick="getSessionData()" ><span class="mdi mdi-file-eye-outline"></span></a>
                        </td>
                        <td>
                            <div>
                                <a href="#uploadSupportingDocs-modal" class="btn-sm btn-primary waves-effect waves-light" data-animation="swell" data-plugin="custommodal" data-overlaySpeed="100" data-overlayColor="#36404a" onclick="addReqData()"  >Upload Supporting</a>
                            </div>
                            <div>
                                <a href="#" class="btn btn-success btn-sm mt-2">Upload Settelment</a>
                            </div>
                            <!-- <a href="#" class="btn btn-primary btn-sm">Upload Settelment</a> -->
                            <!-- <br> -->
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

            <input type="hidden" name="createdBy" id="createdByF" value="">
            <input type="hidden" name="caseId" id="caseIdF" value="">

            <h4 class="custom-modal-title bg-dark">Add Session</h4>
            <div class="custom-modal-text ">

                <span>Session Date :</span>
                <input type="text" autocomplete="off" id="sessionDate" class="form-control" name="sessionDate" placeholder="Select session date">

                <span>Session Time :</span>
                <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime" placeholder="Select session Time">

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


<!-- Modal get addded session data Start -->

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title text-white">Session Records</h4>
                    <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table" id="sessRecId">
                        <thead>
                            <th scope="col">S.No. </th>
                            <th scope="col">Session Date :</th>
                            <th scope="col">Session Time :</th>
                            <th scope="col">Zoom Id :</th>
                            <th scope="col">Note :</th>
                        </thead>
                        <tbody>
                       <!--  <tr>
                            <td><span class="form-control" id="db_sessionDate" ></span></td>
                            <td><span class="form-control" id="db_sessionTime" ></span></td>
                            <td><span class="form-control" id="db_sessionZoomId" ></span></td>
                            <td><span class="form-control" id="db_sessionNote" ></span></td>
                        </tr> -->
                        </tbody>
                    </table>
                    <hr>    
                    <div class="text-center">
                     <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>Close</span>
                    </button>  
                </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

<!-- Modal End -->

<!-- Modal for updload supporting documnets Start -->

    <div id="uploadSupportingDocs-modal" class="modal-demo">

        <button type="button" class="close" onclick="Custombox.modal.close();">
            <span>&times;</span><span class="sr-only">Close</span>
        </button>
        <form id="multi-file-upload-ajax" method="POST"  action="javascript:void(0)" accept-charset="utf-8" enctype="multipart/form-data" >
            @csrf
            <input type="hidden" name="createdBy" id="createdByF1" value="">
            <input type="hidden" name="caseId" id="caseIdF1" value="">

            <h4 class="custom-modal-title bg-dark">Upload Supporting Documnet's</h4>
            <div class="custom-modal-text ">

               <!-- <div class="row"> -->
                <div class="col-sm-12">
                    <div>
                        <!-- <h4 class="header-title mb-4">Default</h4> -->

                        <input type="file" name="files[]" id="files" class="dropify" data-height="150" multiple  />
                    </div>
                </div>
                <!-- end col -->
                <div class="text-center">    
                    <input type="submit" id="submit" name="addSupportingDocs" class="btn-sm btn-primary mt-3">
                </div>
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

    <link href="{{ url('/') }}/assets/libs/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/libs/tooltipster/tooltipster.bundle.min.css" rel="stylesheet" type="text/css">



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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="{{ url('/') }}/assets/libs/dropify/dropify.min.js"></script>
    <script src="{{ url('/') }}/assets/js/pages/form-fileuploads.init.js"></script>

   <script src="{{ url('/') }}/assets/libs/tooltipster/tooltipster.bundle.min.js"></script>
    <script src="{{ url('/') }}/assets/js/pages/tooltipster.init.js"></script>

<script type="text/javascript">
    $(function() {
    $( "#sessionDate").datepicker({ minDate: 0});
  });
</script>

 <script>

    function addReqData(){
        var caseId = $('#caseId').html();
            $('#caseIdF').val(caseId);
            $('#caseIdF1').val(caseId);
        var createdBy = $('#createdBy').val();
            $('#createdByF').val(createdBy);
            $('#createdByF1').val(createdBy);
    }


// create new session
      $(function () {

        $('#addSessionForm').on('submit', function (e) {

          e.preventDefault();

          $.ajax({
            type: 'post',
            url: '{{ route("mediator.addSession") }}',
            data: $('form').serialize(),
            success: function () {
              // alert('form was submitted');
              swal("session created!", {
                        icon: "success",
                });
            }
          });

        });

      });

// <!-- if already set a session a session update -->
function getSessionData(){
    var sheduledBy_Id = $('#createdBy').val();
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    $.ajax({
            type: 'post',
            url: '{{ route("mediator.getAddedSesion") }}',
            data: {mediator_id: sheduledBy_Id, '_token': csrf},
            success: function (data) {
                $('#sessRecId tbody').html(data);
            }

          });
}

// <!-- upload supporing documnets multiple files -->

$(document).ready(function (e) {
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#multi-file-upload-ajax').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        let TotalFiles = $('#files')[0].files.length; //Total files
        let files = $('#files')[0];
        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        $.ajax({
            type:'POST',
            url: '{{ route("mediator.storeMultiFile") }}',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: (data) => {
                this.reset();
                alert('Files has been uploaded using jQuery ajax');
            },
            error: function(data){
                alert(data.responseJSON.errors.files[0]);
                console.log(data.responseJSON.errors);
            }
        });
    });
});
    </script>




@endsection























