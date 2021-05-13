@extends('admin.layouts.app')
@section('title',"Close Request")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">Closed Request</a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('case.serial_number')</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date')</th>
                        <th>@lang('case.case_details')</th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.mediator')</th>
                        <th>@lang('case.comment')</th>
                        <th>@lang('case.session')</th>
                        <th>@lang('case.settlement_agreement')</th>
                        <th>@lang('case.status_logs')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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
            <form id="withdrawForm" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Reason Comment:</label>
                        <textarea class="form-control" name="withdraw_comment"  readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
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
                    <th scope="col">Session Date :</th>
                    <th scope="col">Session Time :</th>
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
<div class="modal fade" id="settelmentModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h4 class="modal-title text-white">Upload Settelment Documents</h4>
                <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="settelmentForm" method="POST"  action="javascript:void(0)" accept-charset="utf-8" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="caseId" id="caseIdF2" value="">
                    <input type="file" name="Settelmentfiles[]" id="Settelmentfiles" class="dropify" data-height="150" multiple  />
                    <br>
                    <input type="submit" id="submit" name="addSupportingDocs" class="btn-sm btn-primary mt-3">
                    <br>
                    <br>
                </form>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection


@section('footer')



<script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Datatables init -->
<script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$(function () {
    $("#sessionDate").datepicker({minDate: 0});
});</script>

<script>

    function pad(str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    var userTable = $('#users').DataTable({
        "ajax": '{{ route("admin.case.json",$confirm_status) }}',
        "responsive": true,
        "order": [[1, "desc"]],
        "columns": [
            {"data": "case.id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
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
                    var button = ` <a href="{{ url('admin/casedetails/') }}/` + data + `" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                    return button;
                }
            },
            {"data": "party",
                render: function (data, type, row) {
                    var d = "";
                    for (i in data) {
                        if (data[i].isOnboarded == 1) {
                            d = d + `<span class="text-success">` + data[i].name + `</span><br>`;
                        } else {
                            d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                        }
                    }
                    return d;
                }
            },
            {"data": "case.mediator_username",
                render: function (data, type, row) {
                    var button = "";
                    button = button + `<button value="` + row.case.id + `"  data-id="` + row.case.id + `" class="btn btn-info btn-sm disabled" disabled>` + data + ` </button>`;
                    if (row.case.mediator_status == 0) {
                        button = button + `<br><span class="badge badge-warning">pending</span> <br> `;
                    } else if (row.case.mediator_status == 1) {
                        button = button + `<br><span class="badge badge-success">Accepted</span> <br> `;
                        button = button + ` <a href="{{ url('admin/consent-and-disclosures/') }}/` + row.case.id + `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">Disclosure</a> `;
                    }
                    return button;
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
                    return button;
                }
            },
            {"data": "case.withdraw",
                render: function (data, type, row) {
                    var button = "";
                    //console.log(data);
                    if (row.status_log.length!=0 && row.status_log[0].status == '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}') {
                        button = button + ` <button value="` + data + `"  data-withdraw="` + data + `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-success waves-effect btn-sm">Withdrawn</button>`;
                    } else if (row.status_log.length!=0 && row.status_log[0].status == '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                        button = button + ` <button value="` + data + `"  data-withdraw="` + data + `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-danger waves-effect btn-sm">Unresolved</button>`;
                    }else {
                        button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case.id + `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">View Supporting</button>`;
                        button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case.id + `" class="btn btn-success waves-effect btn-sm" data-toggle="modal" data-target="#settelmentModal">View Settelment</button>`;
                    }
                    return button;
                }
            },
            {"data": "status_log",
                render: function (data, type, row) {
                    var button = "";
                    for (i in data) {
                        if (data[i].status == '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_ADMIN }}' || data[i].status == '{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}') {
                            button = button + `<span class="badge badge-success">` + data[i].description + ` | At : ` + data[i].created + `</span><br>`;
                        }
                        if (data[i].status == '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}') {
                            button = button + `<span class="badge badge-info ">` + data[i].description + ` | At : ` + data[i].created + `</span><br>`;
                        }
                        if (data[i].status == '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' || data[i].status == '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' || data[i].status == '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' || data[i].status == '{{App\Models\Mediation_status_log::STATUS_UNRESOLVED}}') {
                            button = button + `<span class="badge badge-danger">` + data[i].description + ` | At : ` + data[i].created + `</span><br>`;
                        }
                    }
                    return button;
                }
            },
        ],
    });
    $('#settelmentModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        $.ajax({
            type: 'post',
            url: '{{ route("admin.case.viewSettelment") }}',
            data: {id: recipient},
            success: function (data) {
                $("#settelmentModal tbody").html('');
                $("#settelmentModal tbody").append(data);
                //$("#supportingDocumnet").datatable();
            }
        });
        $('#caseIdF2').val(recipient);
    });
    $('#uploadSupportingDocsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        $.ajax({
            type: 'post',
            url: '{{ route("admin.case.viewSupporting") }}',
            data: {id: recipient},
            success: function (data) {
                $("#supportingDocumnet tbody").html('');
                $("#supportingDocumnet tbody").append(data);
                //$("#supportingDocumnet").datatable();
            }
        });
        $('#caseIdF1').val(recipient);
    });
    $('#settelmentForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let TotalFiles = $('#Settelmentfiles')[0].files.length;
        let files = $('#Settelmentfiles')[0];
        for (let i = 0; i < TotalFiles; i++) {
            formData.append('Settelmentfiles' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        $.ajax({
            type: 'post',
            url: '{{ route("admin.case.settelmen_upload") }}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function () {
                // alert('form was submitted');
                swal("Settelment has been uploaded!", {
                    icon: "success",
                });
                $("#settelmentModal").modal("hide");
                userTable.ajax.reload();
            }
        });
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
            url: '{{ route("admin.case.comment_view") }}',
            data: {type: type, case_id: id},
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
                    url: '{{ route("admin.case.comment") }}',
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
    $('#withdrawModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var withdraw = button.data('withdraw');
        var modal = $(this)
        modal.find('.modal-body textarea[name="withdraw_comment"]').text(withdraw);
    });
    $('#withdrawForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "withdraw this request!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("admin.case.withdraw") }}',
                    data: $('#withdrawForm').serialize(),
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
    $('#addSession-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('id');
        var mediator = button.data('mediator');
        $('#caseIdF').val(recipient);
    });
    $('#addSessionForm').on('submit', function (e) {

        e.preventDefault();
        $.ajax({
            type: 'post',
            url: '{{ route("admin.case.addSession") }}',
            data: $('#addSessionForm').serialize(),
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
            url: '{{ route("admin.case.getAddedSesion") }}',
            data: {mediator_id: sheduledBy_Id, caseid: caseid, '_token': csrf},
            success: function (data) {
                $('#sessRecId tbody').html(data);
            }

        });
    });
</script>
@endsection