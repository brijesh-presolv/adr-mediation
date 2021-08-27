<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Ongoing')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('site.Home')</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('site.Ongoing') </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-md-3">

                <form id="joincode">
                    <div class="form-group">

                                                <div class="input-group mt-3">
                                                    @csrf
                                                    <input type="text" id="joincode" name="joincode" class="form-control" placeholder="@lang('site.Enter the joincode')" required>
                                                    <span class="input-group-append">
                                                            <button type="submit" class="btn waves-effect waves-light btn-primary">@lang('site.GO')</button>
                                                        </span>
                                                </div>

                                            </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <h4 class="header-title"><b>@lang('site.Ongoing') </b></h4>
            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('case.Sr. No')</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date')</th>
                        <th>@lang('case.case_details')</th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.mediator')</th>
                        <th>@lang('case.session')</th>
                        <th>@lang('case.action')</th>
                        <th>@lang('case.status_logs')</th>
                    </tr>
                </thead>
                <tbody>

                    <?php 
                    $i=1;
                    $id='';

                    foreach ($ongoing as $key => $value) {?>
                     <tr>

                        <?php 

                        if($value->caseid==$id){
                            continue;
                        }

                        $id=$value->caseid;

                        ?>
                        <td>{{$i++}}</td>
                        <td><?= 'M'.sprintf('%06d',$value->caseid) ?></td>
                        <td><?= date('d-m-Y',strtotime($value->date))?></td>
                        <td><a class="btn   btn-sm btn-primary label label-success" href="{{route('user.casedetails',$value->caseid)}}">@lang('case.btn_case_details')</a></td>

                        <td><?php

                        if(!isset($value->party)){ ?>

                            <a href="invoke?id=<?= $value->id ?>" class="btn btn-sm btn-danger">@lang('site.Pending')</a>

                        <?php } 


                        if(isset($value->party)){

                        foreach ($value->party as $key => $v) {

                            if($v->isOnboarded==1){
                                echo '<span class="text-success">'.$v->name.'</span></br>';
                            } else{
                                echo '<span class="text-danger">'.$v->name.'</span></br>';
                            }
                            
                        }
                    }



                        ?></td>
                        <td><button class="btn btn-info btn-sm"><?=  $value->mediator  ?></button>

                            <?php if($value->mstatus==0){ ?>
                                <br>
                                <span class="badge badge-warning">@lang('case.status_pending')</span>
                            <?php } else if($value->mstatus==1){ ?>
                                <br>
                                <span class="badge badge-success">@lang('case.status_accepted')</span>

                            <?php } else { ?>

                                 <br>
                                <span class="badge badge-success">@lang('case.status_rejected')</span>

                            <?php } if($value->consent>0){?>
                            <br>
                            <a href="{{route('user.disclosures',$value->caseid)}}" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a>
                        <?php } ?>
                        </td>
                        <td><button value=""  data-id="<?= $value->caseid ?>"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal"  ><span class="mdi mdi-file-eye-outline"></span></button></td>


                        <td>
                             <?php if($value->userid==Auth::user()->id){ ?>
                        <button  class="btn btn-sm btn-inline btn-danger label label-success" data-toggle="modal" data-target="#withdrawModal" data-id="<?= $value->caseid?>">@lang('case.btn_withdraw')</button>
                        <br>
                    <?php } ?>
                    </td>


                        <td>
                            
                            <span class="badge badge-success ">{{$value->casestatus->description}} | @lang('case.At'): {{$value->casestatus->created}}
                        </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="viewSession-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title text-white">@lang('case.session_title')</h4>
                <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="sessRecId">
                    <thead>
                    <th scope="col">@lang('case.serial_number')</th>
                    <th scope="col">@lang('case.scheduling_done_on')</th>
                    <th scope="col">@lang('case.session_scheduled_for')</th>
                    <th scope="col">@lang('case.session_zoom_id')</th>
                    <th scope="col">@lang('case.session_note')</th>
                    <th scope="col">@lang('case.session_meeting_user')</th>
                    </thead>
                    
                    <tbody>
                    </tbody>
                </table>
                <hr>    
                <div class="text-center">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                         <span>@lang('case.btn_close')</span>
                    </button>  
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">@lang('case.withdraw_modal_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="withdrawForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="case_id" class="form-control" >
                    @csrf

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.withdraw_comment'):</label>
                        <textarea class="form-control" name="withdraw_comment"  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                    <button type="submit" class="btn btn-primary">@lang('case.btn_close_request')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

 <!-- Table datatable css -->
@section('head')
  
    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    
@endsection


@section('footer')
 <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

 <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script type="text/javascript">


        $(document).ready(function(){


          $('#users').DataTable();





            $('#viewSession-modal').on('show.bs.modal', function (event) {

        

         var button = $(event.relatedTarget);

         
            var caseid = button.data('id');


    var csrf = document.querySelector('meta[name="csrf-token"]').content;


    $.ajax({
    type: 'post',
            url: '{{ route("user.sessions") }}',
            data: {caseid:caseid, '_token': csrf},
            success: function (data) {
            $('#sessRecId tbody').html(data);
            }

    });

    });

          //join the case


          $('#joincode').on('submit',function(e){

        
                e.preventDefault();


                $.ajax({


                    type: 'post',
                    url: '{{ route("user.join") }}',
                    data: $('#joincode').serialize(),
                    success: function (res) {


                        console.log(res);

                      
                        




                        if(res.response=='success'){

                            swal("Joined successfully!", {
                            icon: "success",
                        }).then(function(){

                            location.reload();
                        })
                        } else if(res.response=='Invalid'){

                            swal("Invalid joincode", {
                            icon: "error",
                        });
                        } else {

                            swal("Please Try Again", {
                            icon: "error",
                        });
                        }

                    },
                    error:function(err){

                        console.log(err);

                    }
                });



          });
            //withdraw the case

            $('#withdrawForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Withdraw case!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("user.case.withdraw") }}',
                    data: $('#withdrawForm').serialize(),
                    success: function (data) {


                        console.log(data);
                        // alert('form was submitted');
                        //userTable.ajax.reload();


                        swal("withdraw successfully!", {
                            icon: "success",
                        }).then(function(){

                            location.reload();
                        })
                    },
                    error:function(err){

                        console.log(err);
                    }
                });
            } else {
                swal("Cancel Withdraw Request!");
            }
        });
        return false;
    });

            $('#withdrawModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-body input[name="case_id"]').val(recipient);
    });



        });
        
    </script>

@endsection























