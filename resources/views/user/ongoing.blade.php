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
@section('page_title', 'Ongoing')

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
                        <th>Select</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date')</th>
                        <th>@lang('case.case_details')</th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.mediator')</th>
                        <th>Comment</th>
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
                        <td><input type="checkbox" class="blkchk" data-caseid="{{$value->caseid}}"></td>
                        <td><?= 'M'.sprintf('%06d',$value->caseid) ?></td>
                        <td><?= date('d-m-Y',strtotime($value->date))?></td>
                        <td><a class="btn   btn-sm btn-primary label label-success" target="_blank" href="{{route('user.casedetails',$value->caseid)}}">@lang('case.btn_case_details')</a></td>

                        <td><?php

                        if(!isset($value->party)){ ?>

                            <a href="invoke?id=<?= $value->id ?>" class="btn btn-sm btn-danger">@lang('site.Pending')</a>

                        <?php } 


                        if(isset($value->party)){

                        foreach ($value->party as $key => $v) {
                            // dd($v);

                            if($v->userId != Auth::user()->id) {
                                if($v->name != "") {
                                    echo '<span class="text-success party_name d-none" data-inid="'.$v->id.'">'.$v->name.'</span>';
                                } 
                            }
                           
                            if($v->isOnboarded==1){
                                if($v->name != "") {
                                echo '<span class="text-success">'.$v->name.'</span></br>';
                                }
                            } else{
                                if($v->name != "") {
                                echo '<span class="text-danger">'.$v->name.'</span></br>';
                                }
                            }
                            
                        }
                    }



                        ?></td>
                        <td><button class="btn btn-info btn-sm"><?=  $value->mediator  ?></button>

                            <?php if($value->mstatus==0){ ?>
                                <br>
                                <span class="badge badge-warning">@lang('case.status_pending')</span>
                            <?php } else if($value->mstatus==1){ $date = date('d-m-Y', strtotime($value->update)); ?>
                                <br>
                                <span class="badge badge-success">@lang('case.status_accepted')</span>
                                <br>
                                <span class="badge badge-success">Date of Consent: {{$date}}</span>

                            <?php } else { $date = date('d-m-Y', strtotime($value->update)); ?>

                                 <br>
                                <span class="badge badge-success">@lang('case.status_rejected')</span>
                                <span class="badge badge-danger">Date of Rejection: {{$date}}</span>

                            <?php } if($value->consent>0){?>
                            <br>
                            <a href="{{route('user.disclosures',$value->caseid)}}" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a>
                        <?php } ?>
                        </td>
                        <td><button type="button" data-type="0", data-typename="Share" data-id="{{$value->caseid}}" data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">Share</button></td>

                        <td><button value=""  data-id="<?= $value->caseid ?>"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal"  ><span class="mdi mdi-file-eye-outline"></span></button></td>

                        <td><button value="{{$value->caseid}}"  data-id="{{$value->caseid}}" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">Upload Supporting</button></td>
                        {{-- <td>
                             <?php if($value->userid==Auth::user()->id){ ?>
                        <button  class="btn btn-sm btn-inline btn-danger label label-success" data-toggle="modal" data-target="#withdrawModal" data-id="<?= $value->caseid?>">@lang('case.btn_withdraw')</button>
                        <br>
                    <?php } ?>
                    </td> --}}


                        <td>
                            
                            <span class="badge badge-success ">@if (isset($value->casestatus->description))
                                
                             {{$value->casestatus->description}} | @lang('case.At'): {{$value->casestatus->created}}@endif</span>
                        </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-2">

                    <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input type="checkbox" id="selectalldir"> Select All Cases</label>

                </div>
                <div class="col-md-4">
                    <button class="blkbtn btn btn-sm btn-inline btn-danger label label-success" data-toggle="modal" data-target="#withdrawModalForBulk" id="bulkWithdrawBtn" style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk Withdraw</button>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadSupportingDocsModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title text-white">Upload Supporting Documents</h4>
                <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="multi-file-upload-ajax" method="POST"  action="javascript:void(0)" accept-charset="utf-8" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="caseId" id="caseIdF1" value="">
                    <input type="file" name="files[]" id="files" class="dropify" data-height="150" multiple required />
                    <br><br>
                    <label>Share With Mediator?</label><input class="ml-2" type="radio" name="shareMediator" id="shareYes" checked value="1">Yes<input class="ml-2" type="radio" name="shareMediator" id="shareNo" value="0">No
                    <br>
                    <span>Share With :</span>
                    <div class="form-group" id="PartyDocs">
                    </div>
                    <input type="submit" id="submit" name="addSupportingDocs" class="btn-sm btn-primary mt-3">
                    <br>
                    <br>
                </form>
                <table class="table table-bordered" id="supportingDocumnet"> 
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">save comment</button>
                </div>
            </form>
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

<div class="modal fade" id="withdrawModalForBulk" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">@lang('case.withdraw_modal_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="withdrawFormForBulk" method="post">
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


        //   $('#users').DataTable();





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
                    beforeSend: function() {

                    swal({
                        title: 'Loading...',
                        showConfirmButton: false,
                        buttons: false,
                        allowOutsideClick: false,
                    });
                    },
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
                    beforeSend: function() {

                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                allowOutsideClick: false,
                            });
                        },
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


        $('#uploadSupportingDocsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        // console.log(recipient);
        var data = button.parent().parent().find(".party_name");
        console.log(data);
        $("#PartyDocs").html("");
        data.each(function () {
            var party_id = $(this).data("inid")
            var party_name = $(this).text()
            var text = `<div class="form-check">
                <input type="checkbox" value="` + party_id + `" class="form-check-input" name="docs_party_ids" id="party" >
                <label class="form-check-label" for="party">` + party_name + `</label>
              </div>`;
            $("#PartyDocs").append(text);
        });
        $.ajax({
            type: 'post',
            url: '{{ route("user.viewSupporting") }}',
            data: {id: recipient, _token: csrf},
            success: function (data) {
                $("#supportingDocumnet tbody").html('');
                $("#supportingDocumnet tbody").append(data);
                //$("#supportingDocumnet").datatable();
            }
        });
        $('#caseIdF1').val(recipient);
    });

    $('#multi-file-upload-ajax').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let TotalFiles = $('#files')[0].files.length;
        let files = $('#files')[0];
        let party = [];
        let shareMediator;
        $("input:checkbox[name=docs_party_ids]:checked").each(function(){
            party.push($(this).val());
        });
        $("input:radio[name=shareMediator]:checked").each(function(){
            shareMediator = $(this).val();
        });
        // if(party == "") {
        //     party = $('#party').val();
        // } else {
        //     party = party + $('#party').val();
        // }
        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        formData.append('docs_party_ids', party);
        formData.append('shareMediator', shareMediator);
        // console.log(party);
        $.ajax({
            type: 'POST',
            url: '{{ route("user.storeMultiFile") }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                        // $('#uploadSupportingDocsModal').modal("hide");
                        $("#uploadSupportingDocsModal").modal("hide");

                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                allowOutsideClick: false,
                            });
                        },
            success: (data) => {
                //this.reset();
                swal("Files has been uploaded!", {
                    icon: "success",
                });
            },
            error: function (data) {
                //alert(data.responseJSON.errors.files[0]);
                console.log(data);
            }
        });
    });
    
    $("#selectalldir").change(function () {
      if (this.checked) {
        $("#bulkWithdrawBtn").show();
        $(".blkchk").each(function () {
          $(this).prop("checked", true);
        });
      } else {
        $(".blkchk").each(function () {
          $(this).prop("checked", false);
        });
        $("#bulkWithdrawBtn").hide();

      }
    });

    $(document).on("change", ".blkchk", function () {
      if (this.checked) {
        $("#bulkWithdrawBtn").show();
      } else {
        $("#bulkWithdrawBtn").hide();
      }
    });

    $('#withdrawFormForBulk').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Withdraw case!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $(".blkchk").each(function () {
                  if (this.checked) {
                    var id = $(this).data("caseid");
                    $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]').val(id);


                    $.ajax({
                        type: 'post',
                        url: '{{ route("user.case.withdraw") }}',
                        data: $('#withdrawFormForBulk').serialize(),
                        beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                
                            });
                        },
                        success: function (data) {

                            swal("withdraw successfully!", {
                                icon: "success",
                            }).then(function(){

                                location.reload();
                            })
                            $('#withdrawModalForBulk').modal("hide");
                        },
                        error:function(err){

                            console.log(err);
                        }
                    });
                  }
                });
            } else {
                swal("Cancel Withdraw Request!");
            }
        });
        return false;
    });

    $('#commentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var typename = button.data('typename');
        var type = button.data('type');
        var modal = $(this);
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        $("#commentView").html("");
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
            }
        });

        modal.find('#commentModalLabel').text(typename);
        modal.find('.modal-body input[name="type"]').val(type);
        modal.find('.modal-body input[name="case_id"]').val(id);
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























