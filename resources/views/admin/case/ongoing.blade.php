@extends('admin.layouts.app')
@section('title',"Ongoing Request")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.ongoing_request')</a></li>
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
                        <th>Select</th>
                        <th>@lang('case.case_id') </th>
                        <th>@lang('case.date') <a href="#" data-toggle="tooltip" title="" data-original-title="Date and time of raising the 'Request for Mediation'."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title="" data-original-title="Click here to view the 'Request for Mediation'."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('case.mediator') <a href="#" data-toggle="tooltip" title="" data-original-title="Click on 'Mediator Name' to withdraw current Mediator and/or appoint new Mediator."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.comment') <a href="#" data-toggle="tooltip" title="" data-original-title="Private comments are for internal reference only. Shared comments are visible to the appointed Mediator. Comments are not visible to the parties.
 "><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.session') <a href="#" data-toggle="tooltip" title="" data-original-title="Schedule meeting date and time. Parties will be notified via email."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.action') </th>
                        <th>@lang('case.status_logs') <a href="#" data-toggle="tooltip" title="" data-original-title="Current status of the Mediation appears here."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                    </tr>
                </thead>
            </table>
            <div class="row">
                <div class="col-md-2">

                    <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input type="checkbox" id="selectalldir"> Select All Cases</label>

                </div>
                <div class="col-md-4">
                    <button class="blkbtn btn btn-teal waves-light waves-effect btn-sm" data-toggle="modal" data-target="#withdrawModalForBulk" id="bulkCloseBtn" style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk Close</button>
                    <button class="blkbtn btn btn-primary waves-light waves-effect btn-sm" data-toggle="modal" data-target="#uploadSupportingDocsModalForBulk" id="bulkUpload" style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload Supporting Documents</button>
                    <button class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target="#addSessionModelForBulk" id="bulkSession" style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>"><span class="mdi mdi-pencil-plus"></span></button>
                
                </div>
                
            </div>
        </div>
    </div>
</div>

<div id="addSessionModelForBulk" class="modal fade"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal-demo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Session</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span><span class="sr-only">Close</span>
                </button>
            </div>

            <form id="addSessionFormForBulk">

                <input type="hidden" name="createdBy" id="createdByF" value="{{Auth::id()}}">
                <input type="hidden" name="caseId"  value="">

                <div class="custom-modal-text ">

                    <span>Session Date :</span>
                    <input type="text" autocomplete="off" id="sessionDateForBulk" class="form-control" name="sessionDate" placeholder="Select session date" data-validation="required">

                    <span>Session Time :</span>
                    <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime" placeholder="Select session Time" data-validation="required">

                    <span>Zoom Id :</span>
                    <input type="text" id="zoomId" class="form-control" name="zoomId" placeholder="Paste meeting Id Or Zoom Id" data-validation="required">

                    <span>Note :</span>
                    <textarea class="form-control" id="note" name="note" placeholder="Add aditional notes"></textarea>
                    {{-- <span>Party :</span>
                    <div id="sessionPartyForBulk">

                    </div> --}}
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

<div class="modal fade" id="uploadSupportingDocsModalForBulk" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
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
                <form id="uploadFormModalForBulk" method="POST"  action="javascript:void(0)" accept-charset="utf-8" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="caseId" id="caseIdF1" value="">
                    <input type="file" name="files[]" id="filesForBulk" class="dropify" data-height="150" multiple  />
                    <br>
                    <input type="submit" id="submit" name="addSupportingDocs" class="btn-sm btn-primary mt-3">
                    <br>
                    <br>
                </form>
                
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
                <h5 class="modal-title" id="commentModalLabel">@lang('case.share_comment_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="commentForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="case_id" class="form-control" >
                    <input type="hidden" name="type" class="form-control" >

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea'):</label>
                        <textarea class="form-control" name="comment"  required></textarea>
                    </div>
                    <div class="row"  id="commentView" style="height: 200px;overflow-x: auto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                    <button type="submit" class="btn btn-primary">@lang('case.btn_save_comment')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">@lang('case.request_close')</h5>
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
                            <option value="{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}">@lang('case.btn_withdrawn')</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">@lang('case.btn_resolved')</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">@lang('case.btn_unresolved')</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea'):</label>
                        <textarea class="form-control" name="withdraw_comment"  ></textarea>
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
                <h5 class="modal-title" id="withdrawModalLabel">@lang('case.request_close')</h5>
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
                            <option value="{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}">@lang('case.btn_withdrawn')</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">@lang('case.btn_resolved')</option>
                            <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">@lang('case.btn_unresolved')</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea'):</label>
                        <textarea class="form-control" name="withdraw_comment"  ></textarea>
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
<div class="modal fade" id="midaterAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('case.mediator_add')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="MidaterForm" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" class="form-control" id="recipient-name">

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.mediator'):</label>
                        <div id="mediatorList"></div>
                        {{-- <select class="form-control" name="midater"  required>
                            <option value="">select Mediator</option>
                            @foreach($users as $user)
                            @if ($user->isActive)
                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} ---</option>
                            @endif
                            @endforeach
                        </select> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                    <button type="submit" class="btn btn-primary">@lang('case.btn_accept')</button>
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
<div id="addSession-modal" class="modal fade"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal-demo">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('case.session_add_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
                </button>
            </div>

            <form id="addSessionForm">

                <input type="hidden" name="createdBy" id="createdByF" value="{{Auth::id()}}">
                <input type="hidden" name="caseId" id="caseIdF" value="">

                <div class="custom-modal-text ">
                    <div class="form-group">
                        <label>@lang('case.session_date') :</label>
                        <input type="text" autocomplete="off" id="sessionDate" class="form-control" name="sessionDate" placeholder="@lang('case.session_date_placeholder')" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label>@lang('case.session_time'):</label>
                        <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime" placeholder="@lang('case.session_time_placeholder')" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label>@lang('case.session_zoom_id') :</label>
                        <input type="text" id="zoomId" class="form-control" name="zoomId" placeholder="@lang('case.session_zoom_id_placeholder')" data-validation="required">
                    </div>
                    <div class="form-group">
                        <label>@lang('case.session_note'):</label>
                        <textarea class="form-control" id="note" name="note" placeholder="@lang('case.session_note_placeholder')"></textarea>
                    </div>
                    <span>@lang('case.session_party'):</span>
                    <div class="form-group" id="sessionParty">
                    </div>
                    <div class="text-center">    
                        <input type="submit" name="@lang('case.session_add_title')" class="btn-sm btn-primary mt-3">
                    </div>
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
    $("#sessionDate").datepicker({minDate: 0, dateFormat: 'dd/mm/yy'});
    $("#sessionDateForBulk").datepicker({minDate: 0, dateFormat: 'dd/mm/yy'});

});</script>
<script>
    $.validate();
</script>
<script>

    function pad(str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }

    var userTable = $('#users').DataTable({
        "ajax": '{{ route("admin.case.json",$confirm_status) }}',
        "responsive": true,
        // "order": [[1, "desc"]],
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
                    var button = "M" + pad(data, 6);
                    button = button+`<br><a href="{{ url('admin/track/') }}/` + data + `" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a> `
                    return button;
                }
            },
            {"data": "date"},
            {"data": "case.id",
                render: function (data) {
                    var button = ` <a href="{{ url('admin/casedetails/') }}/` + data + `" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                    button = button+ ` <a href="{{ url('admin/updatecase/') }}/` + data + `" class="btn btn-info waves-effect waves-light btn-sm" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                    return button;
                }
            },
            {"data": "party",
                render: function (data, type, row) {
                    var d = "";
                    for (i in data) {
                        if (data[i].userId != 0) {
                            d = d + `<span class="text-success party_name" data-id="` + data[i].userId + `">` + data[i].name + `</span><br>`;
                        } else {
                            d = d + `<span class="text-danger" data-id="` + data[i].userId + `">` + data[i].name + `</span><br>`;
                        }
                    }
                    return d;
                }
            },
            {"data": "case.mediator_username",
                render: function (data, type, row) {
                    var button = "";
                    button = button + `<button value="` + row.case.id + `"  data-id="` + row.case.id + `" data-mediator="` + row.case.mediator_id + `" data-toggle="modal" data-target="#midaterAdd" class="btn btn-info btn-sm">` + data + ` </button>`;
                    if (row.case.mediator_status == 0) {
                        button = button + `<br><span class="badge badge-warning">@lang('case.status_pending')</span><br> `;
                    } else if (row.case.mediator_status == 1) {
                        button = button + `<br><span class="badge badge-success">@lang('case.status_accepted')</span><br> `;
                        button = button + ` <a href="{{ url('admin/consent-and-disclosures/') }}/` + row.case.id + `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                    } else {
                        button = button + `<br><span class="badge badge-danger">@lang('case.status_rejected')</span>`;
                    }
                    return button;
                }
            },
            {"data": "case.id",
                render: function (data, type, row) {
                    var button = "";
                    button = button + ` <button type="button"  data-type="1" data-typename="Private" data-id="` + data + `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">@lang('case.btn_private')</button>`;
                    button = button + ` <button type="button" data-type="0" data-typename="Share" data-id="` + data + `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark waves-effect btn-sm">@lang('case.btn_share')</button>`;
                    return button;
                }
            },
            {"data": "case.id",
                render: function (data, type, row) {
                    var button = "";
                    button = button + ` <button value="` + data + `"  data-id="` + data + `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal" title="@lang('case.btn_session_view')" ><span class="mdi mdi-file-eye-outline"></span></button>`;
                    button = button + ` <button value="` + data + `"  data-id="` + data + `"   class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target="#addSession-modal" title="@lang('case.btn_session_add')"><span class="mdi mdi-pencil-plus"></span></button>`;
                    return button;
                }
            },
            {"data": "case.id",
                render: function (data, type, row) {
                    var button = "";
                    button = button + ` <button value="` + data + `"  data-id="` + data + `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">Upload Supporting</button>`;
                    button = button + `<button value="` + data + `"  data-id="` + data + `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-teal waves-light waves-effect btn-sm">@lang('case.btn_close')</button>`;
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


    $("#selectalldir").change(function () {
      if (this.checked) {
        $("#bulkCloseBtn").show();
        $("#bulkUpload").show();
        $("#bulkSession").show();

        $(".blkchk").each(function () {
          $(this).prop("checked", true);
        });
      } else {
        $(".blkchk").each(function () {
          $(this).prop("checked", false);
        });
        $("#bulkCloseBtn").hide();
        $("#bulkUpload").hide();
        $("#bulkSession").hide();

      }
    });

    $(document).on("change", ".blkchk", function () {
      if (this.checked) {
        $("#bulkCloseBtn").show();
        $("#bulkUpload").show();
        $("#bulkSession").show();

      } else {
        $("#bulkCloseBtn").hide();
        $("#bulkUpload").hide();
        $("#bulkSession").hide();

      }
    });
    $('#addSessionFormForBulk').on('submit', function (e) {
        e.preventDefault();
        
        $(".blkchk").each(function () {
            if (this.checked) {
                var id = $(this).data("caseid");
                $('#addSessionFormForBulk').find('input[name="caseId"]').val(id);
                $.ajax({
                    type: 'post',
                    url: '{{ route("admin.case.addSession") }}',
                    data: $('#addSessionFormForBulk').serialize(),
                    beforeSend: function() {
                        $('#addSessionModelForBulk').modal("hide");

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
                        }).then(function () {
                            location.reload();
                        });
                        // $('#addSession-modal').modal("hide");
                    }
                });
            }
            
        });
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
                        url: '{{ route("admin.case.withdraw") }}',
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

    $('#uploadFormModalForBulk').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        let TotalFiles = $('#filesForBulk')[0].files.length;
        let files = $('#filesForBulk')[0];
        for (let i = 0; i < TotalFiles; i++) {
            formData.append('files' + i, files.files[i]);
        }
        formData.append('TotalFiles', TotalFiles);
        $(".blkchk").each(function () {
            if (this.checked) {
                formData.delete('caseId');
                var id = $(this).data("caseid");
                // $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]').val(id);
                formData.append('caseId', id);

                $.ajax({
                    type: 'POST',
                    url: '{{ route("admin.case.storeMultiFile") }}',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: (data) => {
                        //this.reset();
                        swal("Files has been uploaded!", {
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                        $("#uploadSupportingDocsModalForBulk").modal("hide");
                    },
                    error: function (data) {
                        //alert(data.responseJSON.errors.files[0]);
                        console.log(data);
                    }
                });
            }
            
        });
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
            url: '{{ route("admin.case.storeMultiFile") }}',
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

    $(document).on('submit', "#MidaterForm", function () {
        var id = $(this).find("input[name='id']").val();
        var midater = $(this).find("select[name='midater']").val();
        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        swal({
            title: "@lang('case.are_you_sure')",
            text: "@lang('case.canform_this_request')",
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
                    swal("@lang('case.mediator_assigned_successfully')", {
                        icon: "success",
                    });
                    userTable.ajax.reload(null, false);
                    $('#midaterAdd').modal("hide");
                });
            } else {
                swal("@lang('case.cansel_confirm_request')");
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
            title: "@lang('case.are_you_sure')",
            text: "@lang('case.add_this_comment')",
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
                        $('#commentForm')[0].reset();
                        swal("@lang('case.comment_save_successfully')", {
                            icon: "success",
                        });
                        $('#commentModal').modal("hide");
                    }
                });
            } else {
                swal("@lang('case.comment_not_added')");
            }
        });
        return false;
    });
    $('#withdrawForm').on('submit', function (e) {
        e.preventDefault();
        swal({
            title: "@lang('case.are_you_sure')",
            text: "@lang('case.change_status')",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: 'post',
                    url: '{{ route("admin.case.withdraw") }}',
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
                        userTable.ajax.reload(null, false);
                        swal("@lang('case.status_change_successfully')", {
                            icon: "success",
                        });
                        $('#withdrawModal').modal("hide");
                    }
                });
            } else {
                swal("@lang('case.request_canseled')");
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
        // modal.find('.modal-body select[name="midater"]').val(mediator);
        var users=<?php echo json_encode($users); ?>;
        var htmlData="<select class='form-control' name='midater'  required><option value=''>@lang('case.form_select_mediator')</option>";
            users.forEach(function(item, index) {
                    if (item.isActive) {
                        if (mediator===item.id) {
                            htmlData+="<option value='"+item.id+"' disabled style='background-color:#d6d2d2'>"+item.first_name+" "+item.last_name+" - "+item.organization+"</option>";
                        }else{
                            htmlData+="<option value='"+item.id+"'>"+item.first_name+" "+item.last_name+" - "+item.organization+"</option>";
                        }
                    }
            });
        htmlData+="</select>";
        document.getElementById("mediatorList").innerHTML = htmlData;

    });
    $('#addSession-modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var data = button.parent().parent().find(".party_name");
        $("#sessionParty").html("");
        data.each(function () {
            var party_id = $(this).data("id")
            var party_name = $(this).text()
            var text = `<div class="form-check">
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
            url: '{{ route("admin.case.addSession") }}',
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
                swal("@lang('case.session_created')", {
                    icon: "success",
                });
                $('#addSessionForm')[0].reset();
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