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
@section('page_title', 'Closed')

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
                        <th>Comment</th>

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
                                if($v->name != "") {
                                echo '<span class="text-success">'.$v->name.'</span></br>';
                                }
                            } 
                            else{
                                if($v->name != "") {
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
                            <?php } else if($value->mstatus==1){ $date = date('d-m-Y', strtotime($value->create));?>
                                <br>
                                <span class="badge badge-success">@lang('case.status_accepted')</span>
                                <br>
                                <span class="badge badge-success">Date of Consent: {{$date}}</span>
                            <?php } else { ?>

                                 <br>
                                <span class="badge badge-danger">@lang('case.status_rejected')</span>
                                {{-- <br><span class="badge badge-danger">Date of Rejection: {{$date}}</span> --}}

                            <?php } if($value->consent>0){?>

                            <br>
                            <a href="{{route('user.disclosures',$value->caseid)}}" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">Disclosure</a>
                        <?php } ?>
                        </td>
                        <td> <div class="position-relative"><button type="button" data-type="0", data-typename="Share" data-id="{{$value->caseid}}" data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">Share</button>
                        @if ($value->share_view_count != 0)    
                        <span class="badge badge-danger share_unseen">{{$value->share_view_count}}</span>
                        @endif
                        <span class="badge-success badge share_total">{{$value->share_count}}</span>
                        </div></td>
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

<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Share</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="commentForm" method="post">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="case_id" class="form-control" >
                    <input type="hidden" name="type" class="form-control" >

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Comment:</label>
                        <textarea class="form-control" name="comment"  required></textarea>
                    </div>
                    <div class="row" id="commentView" style="height: 200px;overflow-x: auto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="commentModal-close" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">save comment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade h-75" id="viewSession-modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>Close</span>
                    </button>  
                <div id="sessionShowBtn" class="text-center"></div>
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
                var pdfButton = "";
                if(data != "") {
                    // console.log(caseid);
                    var link = '{{route("user.case.sessionPdf", '')}}'+'/'+caseid;
                    // console.log(link);
                    pdfButton = "<a target='_blank' href='"+link+"' class='btn btn-success'><span>Download PDF</span></button>"
                    $('#sessRecId tbody').html(data);
                    $('#sessionShowBtn').html(pdfButton);
                } else {
                    $('#sessRecId tbody').html("No Session");
                    $('#sessionShowBtn').html(pdfButton);

                }
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

        $('#commentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var typename = button.data('typename');
        var type = button.data('type');
        var modal = $(this);
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        var urlpdf = '{{route("user.case.commentPDF",'','')}}'+'/'+id+'/'+type;

        $("#commentView").html("");
        $('#commentForm .modal-footer #DownLoadPdf').remove();

        $.ajax({
            type: 'post',
            url: '{{ route("user.case.comment_view") }}',
            data: {type: type, case_id: id, _token: csrf},
            success: function (data) {
                for (i in data) {
                    //console.log(data[0]);
                    if (data[i].username == '{{Auth::user()->username}}') {
                        var msg = `<div class="col-md-12 text-right border-top">
                                <div class="row">
                                                <div class="col-md-4 text-left"><small class="text-muted">` + data[i].created + `</small></div>
                                                <div class="col-md-8"><small class="text-muted">` + data[i].username + `</small></div>
                                    </div>           
                                    <p>` + data[i].comment + `</p>
                            </div>`;
                        $("#commentView").append(msg);
                    } else {
                        var msg = `<div class="col-md-12 border-top">
                                <div class="row">
                                                <div class="col-md-8"><small class="text-muted">` + data[i].username + `</small></div>
                                                <div class="col-md-4 text-right"><small class="text-muted">` + data[i].created + `</small></div>
                                    </div>           
                                    <p>` + data[i].comment + `</p>
                            </div>`;
                        $("#commentView").append(msg);
                    }
                }
                if(data[i] != null){
                        $('#commentForm .modal-footer').append("<a href="+urlpdf+"><button type='button' class='btn btn-success' id='DownLoadPdf'>Download Comment</button></a>");
                }
            }
        });

        modal.find('#commentModalLabel').text(typename);
        modal.find('.modal-body input[name="type"]').val(type);
        modal.find('.modal-body input[name="case_id"]').val(id);
    });
    
    $('#commentModal-close').on('click', function() {
        location.reload();
    });

    $('#commentForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "add this comment!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("user.case.comment") }}',
                    data: $('#commentForm').serialize(),
                    success: function () {
                        swal("comment save successfully!", {
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                        $('#commentForm')[0].reset();
                        $('#commentModal').modal("hide");
                    }
                });
            } else {
                swal("comment not added!");
            }
        });
        return false;
    });
        
    </script>

@endsection























