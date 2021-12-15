<?php
use App\Models\InvoledUser;
?>
@extends('user.layouts.app')
@section('title', 'Closed')

@section('breadcrumb')
      <!-- start page title -->
       <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
       <li class="breadcrumb-item"><a href="javascript: void(0);">Closed </a></li>
    <!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">

            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Date</th>
                        <th>Case Details</th>
                        <th>Party Details</th>
                        <th>Mediator</th>

                        <th>Session</th>
                        <th>Settelment Agreement</th>

                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                     <?php 
                    $i=1;

                    foreach ($closed as $key => $value) { ?>
                    <tr>
                        <td>{{$i++}}</td>
                        <td><?= 'M'.sprintf('%06d',$value->caseid) ?></td>
                        <td><?= date('d-m-Y',strtotime($value->date))?></td>
                        <td><a class="btn   btn-sm btn-primary label label-success" target="_blank" href="{{route('user.casedetails',$value->caseid)}}">View</a></td>

                        
                        <td><?php


    
                        foreach ($value->party as $key => $v) {

                            if($v->isOnboarded==1){
                                if($v->name) {
                                echo '<span class="text-success">'.$v->name.'</span></br>';
                                }
                            } else{
                                if($v->name) {
                                echo '<span class="text-danger">'.$v->name.'</span></br>';
                                }
                            }
                            
                        }



                        ?></td>
                        <td>

                            <button class="btn btn-info btn-sm"><?=  $value->mediator  ?></button>
                            
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
                            <a href="{{route('user.disclosures',$value->caseid)}}" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">Disclosure</a>
                        <?php } ?>
                        </td>
                        <td><button value=""  data-id="<?= $value->caseid ?>"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal"  ><span class="mdi mdi-file-eye-outline"></span></button></td>
                        <td>

                            <?php 


                            if ($value->casestatus->status==6){?>

                            <button value="<?= $value->caseid ?>"  data-id="<?= $value->caseid ?>" class="btn btn-success waves-effect btn-sm" data-toggle="modal" data-target="#settelmentModal">View</button>
                        <?php }  else if ($value->casestatus->status==5){ ?>


                            <button value="Comment" data-withdraw="{{$value->withdraw}}" data-toggle="modal" data-target="#withdrawModal" class="btn btn-success waves-effect btn-sm">Withdrawn</button>
                       <?php } else if ($value->casestatus->status==7){?>
                            <button value="Comment" data-withdraw="{{$value->withdraw}}" data-toggle="modal" data-target="#withdrawModal" class="btn btn-danger waves-effect btn-sm">Unresolved</button>

                       <?php } ?>

                        </td>
                        <td>
                            
                            <span class="badge badge-{{$value->casestatus->css}} ">{{$value->casestatus->description}} | At: {{$value->casestatus->created}}
                            </span>
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
                    <th scope="col">Scheduling done on:</th>
                    <th scope="col">Session scheduled for:</th>
                    <th scope="col">Zoom Id :</th>
                    <th scope="col">Note :</th>
                    <th scope="col">Party</th>
                    </thead>
                    <tbody>
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


<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                   <p id="withdrawreason"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="settelmentModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title text-white">Settelment Agreemnet</h4>
                <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered"> 
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>file</th>
                            <th>Upload By</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                    <span>Close</span>
                </button> 
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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



            $('#withdrawModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var withdraw = button.data('withdraw');
        var modal = $(this)
        modal.find('#withdrawreason').text(withdraw);
    });

            $('#settelmentModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget);
        var recipient = button.data('id');

        var csrf = document.querySelector('meta[name="csrf-token"]').content;

        

        $.ajax({
            type: 'post',
            url: '{{ route("user.viewSettelment") }}',
            data: {id: recipient,'_token': csrf},
            success: function (data) {


                $("#settelmentModal tbody").html('');
                $("#settelmentModal tbody").append(data);
                //$("#supportingDocumnet").datatable();
            }
        });
        $('#caseIdF2').val(recipient);
    });


           



        });
        
    </script>

@endsection























