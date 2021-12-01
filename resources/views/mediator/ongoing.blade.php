@extends('mediator.layouts.app')
@section('title',"Ongoing Request")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Ongoing Request</a></li>
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
                        <th>Select</th>
                        <th>Case Id</th>
                        <th>Date</th>
                        <th>Case Details</th>
                        <th>Party Details</th>
                        <th>Comment</th>
                        <th>Session</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
            <div class="row">
                <div class="col-md-2">

                    <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input type="checkbox" id="selectalldir"> Select All Cases</label>

                </div>
                <div class="col-md-4">
                    <button class="blkbtn btn btn-teal waves-light waves-effect btn-sm" data-toggle="modal" data-target="#withdrawModalForBulk" id="bulkCloseBtn" style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk Close</button>
                </div>
                
            </div>
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
                    <input type="file" name="files[]" id="files" class="dropify" data-height="150" multiple  />
                    <br>
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
                    <th scope="col">Meeting user</th>
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
<div id="addSession-modal" class="modal fade"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal-demo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span><span class="sr-only">Close</span>
                </button>
            </div>

            <form id="addSessionForm">

                <input type="hidden" name="createdBy" id="createdByF" value="{{Auth::id()}}">
                <input type="hidden" name="caseId" id="caseIdF" value="">

                <div class="custom-modal-text ">

                    <span>Session Date :</span>
                    <input type="text" autocomplete="off" id="sessionDate" class="form-control" name="sessionDate" placeholder="Select session date" data-validation="required">

                    <span>Session Time :</span>
                    <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime" placeholder="Select session Time" data-validation="required">

                    <span>Zoom Id :</span>
                    <input type="text" id="zoomId" class="form-control" name="zoomId" placeholder="Paste meeting Id Or Zoom Id" data-validation="required">

                    <span>Note :</span>
                    <textarea class="form-control" id="note" name="note" placeholder="Add aditional notes"></textarea>
                    <span>Party :</span>
                    <div id="sessionParty">

                    </div>
                    <div class="text-center">    
                        <input type="submit" name="addSession" class="btn-sm btn-primary mt-3">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Request Close</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="withdrawForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="case_id" class="form-control" >
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Status:</label>
                        <select class="form-control" name="status"  required>
                            <option value="">---select status---</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">Resolved</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">Unresolved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Comment:</label>
                        <textarea class="form-control" name="withdraw_comment"  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Close Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="withdrawModalForBulk" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Request Close</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="withdrawFormForBulk" method="post">
                <div class="modal-body">
                    <input type="hidden" name="case_id" class="form-control" >
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Status:</label>
                        <select class="form-control" name="status"  required>
                            <option value="">---select status---</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">Resolved</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">Unresolved</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Comment:</label>
                        <textarea class="form-control" name="withdraw_comment"  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Close Request</button>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link href="{{ url('/') }}/assets/libs/dropify/dropify.min.css" rel="stylesheet" type="text/css" />
@endsection


@section('footer')



<script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>


<!-- Datatables init -->

<script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('/') }}/assets/libs/dropify/dropify.min.js"></script>
<script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
$(function () {
    $("#sessionDate").datepicker({minDate: 0, dateFormat: 'dd/mm/yy'});
});
</script>
<script>
    $.validate();
</script>
<script>

    function pad(str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    var userTable = $('#users').DataTable({
        "ajax": '{{ route("mediator.case.jsonOngoing",$confirm_status) }}',
        "responsive": true,
        
        "columns": [
            {"data": "case.id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {"data": "case",
                render: function (data, type, row) {
                    var button = "";
                    button = button + `<input type="checkbox" class="blkchk" data-caseid="` + data.id + `">`;
                    return button;
                }
            },
            {"data": "case.id",
                render: function (data) {
                    return "M" + pad(data, 6);
                }
            },
            {"data": "date"},
            {"data": "case.id",
                render: function (data) {
                    var button = "";
                    button = button + `<a href="{{ url('mediator/casedetails/') }}/` + data + `" class="btn btn-primary waves-effect  waves-light btn-sm"><i class="mdi mdi-file-eye-outline"></i></a> `;
                    button = button + `<a href="{{ url('mediator/consent-and-disclosures/') }}/` + data + `" target="_blank" class="btn btn-teal waves-light waves-effect btn-sm">Disclosure</a> `;
                    return button;
                }
            },
            {"data": "party",
                render: function (data, type, row) {
                    var d = "";
                    for (i in data) {
                        if (data[i].userId != 0) {
                            if(data[i].name != null) {
                            d = d + `<span class="text-success party_name" data-id="` + data[i].userId + `">` + data[i].name + `</span><br>`;
                            }
                        } else {
                            if(data[i].name != null) {
                            d = d + `<span class="text-danger" data-id="` + data[i].userId + `">` + data[i].name + `</span><br>`;
                            }
                        }
                    }
                    return d;
                }
            },
            {"data": "case.id",
                render: function (data, type, row) {
                    var button = "";
                    button = button + ` <button type="button"  data-type="1" data-typename="Private" data-id="` + data + `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">Private</button>`;
                    button = button + ` <button type="button" data-type="0" data-typename="Share" data-id="` + data + `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark waves-effect btn-sm">Share</button>`;
                    return button;
                }
            },
            {"data": "case.id",
                render: function (data, type, row) {
                    var button = "";
                    button = button + ` <button value="` + data + `"  data-id="` + data + `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal"  ><span class="mdi mdi-file-eye-outline"></span></button>`;
                    button = button + ` <button value="` + data + `"  data-id="` + data + `"   class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target="#addSession-modal" ><span class="mdi mdi-pencil-plus"></span></button>`;
                    return button;
                }
            },
            {"data": "case.id",
                render: function (data, type, row) {
                    var button = "";
                    button = button + ` <button value="` + data + `"  data-id="` + data + `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">Upload Supporting</button>`;
                    button = button + ` <button value="` + data + `"  data-id="` + data + `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-teal waves-light waves-effect btn-sm">Close</button>`;
                    return button;
                }
            },
        ],
    });


    $("#selectalldir").change(function () {
      if (this.checked) {
        $("#bulkCloseBtn").show();
        $(".blkchk").each(function () {
          $(this).prop("checked", true);
        });
      } else {
        $(".blkchk").each(function () {
          $(this).prop("checked", false);
        });
        $("#bulkCloseBtn").hide();

      }
    });

    $(document).on("change", ".blkchk", function () {
      if (this.checked) {
        $("#bulkCloseBtn").show();
      } else {
        $("#bulkCloseBtn").hide();
      }
    });

    $('#withdrawFormForBulk').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "@lang('case.are_you_sure')",
            text: "@lang('case.change_status')",
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
                        url: '{{ route("mediator.case.withdraw") }}',
                        data: $('#withdrawFormForBulk').serialize(),
                        beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                
                            });
                        },
                        success: function () {
                            // alert('form was submitted');
                            userTable.ajax.reload(null, false);
                            swal("@lang('case.status_change_successfully')", {
                                icon: "success",
                            });
                            $('#withdrawModalForBulk').modal("hide");
                        }
                    });
                  }
                });
            } else {
                swal("@lang('case.request_canseled')");
            }
        });
        return false;
    });


    $(document).on('submit', "#MidaterForm", function () {
        var id = $(this).find("input[name='id']").val();
        var midater = $(this).find("select[name='midater']").val();
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        swal({
            title: "Are you sure?",
            text: "Canform this request!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: '{{ route("admin.case.midater_add") }}',
                    method: "post",
                    data: {id: id, midater: midater, '_token': csrf},
                }).done(function (data) {
                    swal("mediator assigned successfully!", {
                        icon: "success",
                    });
                    userTable.ajax.reload();
                    $('#midaterAdd').modal("hide");
                });
            } else {
                swal("Cansel Confirm Request!");
            }
        });
        return false;
    });
    $('#multi-file-upload-ajax').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let TotalFiles = $('#files')[0].files.length;
        let files = $('#files')[0];
        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        $.ajax({
            type: 'POST',
            url: '{{ route("mediator.storeMultiFile") }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: (data) => {
                //this.reset();
                swal("Files has been uploaded!", {
                    icon: "success",
                });
                $("#uploadSupportingDocsModal").modal("hide");
            },
            error: function (data) {
                //alert(data.responseJSON.errors.files[0]);
                console.log(data);
            }
        });
    });

    $('#withdrawModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var modal = $(this)
        modal.find('.modal-body input[name="case_id"]').val(recipient);
    });
    $('#commentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var typename = button.data('typename');
        var type = button.data('type');
        var modal = $(this)
        $("#commentView").html("");
        $.ajax({
            type: 'post',
            url: '{{ route("mediator.case.comment_view") }}',
            data: {type: type, case_id: id},
            success: function (data) {
                for (i in data) {
                    //console.log(data[0]);
                    if (data[i].username == '{{Auth::user()->username}}') {
                        var msg = `<div class="col-md-12 text-righ border-topt">
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
                    url: '{{ route("mediator.case.comment") }}',
                    data: $('#commentForm').serialize(),
                    success: function () {
                        $('#commentForm')[0].reset();
                        swal("comment save successfully!", {
                            icon: "success",
                        });
                        $('#commentModal').modal("hide");
                    }
                });
            } else {
                swal("comment not added!");
            }
        });
        return false;
    });
    $('#withdrawForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Change status!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("mediator.case.withdraw") }}',
                    data: $('#withdrawForm').serialize(),
                    beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                
                            });
                        },
                    success: function () {
                        // alert('form was submitted');
                        userTable.ajax.reload();
                        swal("status change successfully!", {
                            icon: "success",
                        });
                        $('#withdrawModal').modal("hide");
                    }
                });
            } else {
                swal("Request Canseled!");
            }
        });
        return false;
    });

    $('#midaterAdd').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var mediator = button.data('mediator');
        var modal = $(this)
        modal.find('.modal-body input[name="id"]').val(recipient);
        modal.find('.modal-body select[name="midater"]').val(mediator);
    });
    $('#uploadSupportingDocsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        $.ajax({
            type: 'post',
            url: '{{ route("mediator.viewSupporting") }}',
            data: {id: recipient},
            success: function (data) {
                $("#supportingDocumnet tbody").html('');
                $("#supportingDocumnet tbody").append(data);
                //$("#supportingDocumnet").datatable();
            }
        });
        $('#caseIdF1').val(recipient);
    });

    $('#addSession-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var data = button.parent().parent().find(".party_name");
        $("#sessionParty").html("");
        data.each(function () {
            var party_id = $(this).data("id")
            var party_name = $(this).text()
            var text = `<div class="form-group form-check">
                <input type="checkbox" value="` + party_id + `" class="form-check-input" name="session_party_ids[]" id="party` + party_id + `" data-validation="checkbox_group" data-validation-qty="min1">
                <label class="form-check-label" for="party` + party_id + `">` + party_name + `</label>
              </div>`;
            $("#sessionParty").append(text);
        });
        var recipient = button.data('id');
        var mediator = button.data('mediator');
        $('#caseIdF').val(recipient);
    });
    $('#addSessionForm').on('submit', function (e) {

        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '{{ route("mediator.case.addSession") }}',
            data: $('#addSessionForm').serialize(),
            beforeSend: function() {
                $('#addSession-modal').modal("hide");

                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,
                                allowOutsideClick: false,
                            });
                        },
            success: function () {
                // alert('form was submitted');
                swal("session created!", {
                    icon: "success",
                });
                $('#addSession-modal').modal("hide");
            }
        });
    });
    $('#viewSession-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var caseid = button.data('id')
        var sheduledBy_Id = '{{Auth::id()}}';
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        $.ajax({
            type: 'post',
            url: '{{ route("mediator.case.getAddedSesion") }}',
            data: {mediator_id: sheduledBy_Id, caseid: caseid, '_token': csrf},
            success: function (data) {
                $('#sessRecId tbody').html(data);
            }

        });
    });
</script>
@endsection