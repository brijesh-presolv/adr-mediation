@extends('mediator.layouts.app')
@section('title', 'Ongoing Request')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">Ongoing Request</a></li>
    <!-- end page title -->
@endsection
@section('pageTitleOnDashboard', 'Ongoing Request')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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

                        <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input
                                type="checkbox" id="selectalldir"> Select All Cases</label>

                    </div>
                    <div class="col-md-4">
                        <button class="blkbtn btn btn-teal waves-light waves-effect btn-sm" data-toggle="modal"
                            data-target="#withdrawModalForBulk" id="bulkCloseBtn" style="margin-top:10px; display:none;"
                            data-arb="<?= Auth::user()->id ?>">Bulk Close</button>
                        <button class="blkbtn btn btn-primary waves-light waves-effect btn-sm" data-toggle="modal"
                            data-target="#uploadSupportingDocsModalForBulk" id="bulkUpload"
                            style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload Supporting
                            Documents</button>
                        <button class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal"
                            data-target="#addSessionModelForBulk" id="bulkSession" style="margin-top:10px; display:none;"
                            data-arb="<?= Auth::user()->id ?>"><span class="mdi mdi-pencil-plus"></span></button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="addSessionModelForBulk" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal-demo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                </div>

                <form id="addSessionFormForBulk">

                    <input type="hidden" name="createdBy" id="createdByF" value="{{ Auth::id() }}">
                    <input type="hidden" name="caseId" value="">

                    <div class="custom-modal-text ">

                        <span>Session Date :</span>
                        <input type="text" autocomplete="off" id="sessionDateForBulk" class="form-control"
                            name="sessionDate" placeholder="Select session date" data-validation="required">

                        <span>Session Time :</span>
                        <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime"
                            placeholder="Select session Time" data-validation="required">

                        <span>Zoom Id :</span>
                        <input type="text" id="zoomId" class="form-control" name="zoomId"
                            placeholder="Paste meeting Id Or Zoom Id" data-validation="required">

                        <span>Note :</span>
                        <textarea class="form-control" id="note" name="note" placeholder="Add aditional notes"
                            data-validation="required"></textarea>
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



    <div class="modal fade" id="uploadSupportingDocsModalForBulk" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
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
                    <form id="uploadFormModalForBulk" method="POST" action="javascript:void(0)" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="caseId" value="">
                        <input type="file" name="files[]" id="filesForBulk" class="dropify" data-height="150"
                            multiple />
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
                    <h5 class="modal-title" id="commentModalLabel">Share</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="commentForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="case_id" class="form-control">
                        <input type="hidden" name="type" class="form-control">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Comment:</label>
                            <textarea class="form-control" name="comment" required></textarea>
                        </div>
                        <div class="row" id="commentView" style="height: 200px;overflow-x: auto">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="commentModal-close"
                            data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">save comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uploadSupportingDocsModal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
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
                    <form id="multi-file-upload-ajax" method="POST" action="javascript:void(0)" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="caseId" id="caseIdF1" value="">
                        <input type="file" name="files[]" id="files" class="dropify" data-height="150" multiple
                            required />
                        <br><br>
                        <span>Share With:</span>
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

    <div class="modal fade h-75" id="viewSession-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
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
                            <th scope="col">Meeting user</th>
                            <th scope="col">Action</th>
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
    <div id="addSession-modal" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        class="modal-demo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                </div>

                <form id="addSessionForm">

                    <input type="hidden" name="createdBy" id="createdByF" value="{{ Auth::id() }}">
                    <input type="hidden" name="caseId" id="caseIdF" value="">

                    <div class="custom-modal-text ">

                        <span>Session Date :</span>
                        <input type="text" autocomplete="off" id="sessionDate" class="form-control" name="sessionDate"
                            placeholder="Select session date" data-validation="required">

                        <span>Session Time :</span>
                        <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime"
                            placeholder="Select session Time" data-validation="required">

                        <span>Zoom Id :</span>
                        <input type="text" id="zoomId" class="form-control" name="zoomId"
                            placeholder="Paste meeting Id Or Zoom Id" data-validation="required">

                        <span>Note :</span>
                        <textarea class="form-control" id="note" name="note" placeholder="Add aditional notes"
                            data-validation="required"></textarea>
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
                        <input type="hidden" name="case_id" class="form-control">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <select class="form-control" name="status" required>
                                <option value="">---select status---</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">Resolved</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">Unresolved
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Comment:</label>
                            <textarea class="form-control" name="withdraw_comment" required></textarea>
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
    <div class="modal fade" id="withdrawModalForBulk" tabindex="-1" aria-labelledby="withdrawModalLabel"
        aria-hidden="true">
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
                        <input type="hidden" name="case_id" class="form-control">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <select class="form-control" name="status" required>
                                <option value="">---select status---</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">Resolved</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">Unresolved
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Comment:</label>
                            <textarea class="form-control" name="withdraw_comment" required></textarea>
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

    <div id="Session-edit-mediator" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel1"
        aria-hidden="true" class="modal-demo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Update Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
                    </button>
                </div>

                <form id="UpdateSessionForm">

                    <input type="hidden" name="createdBy" id="editcreatedByF" value="{{ Auth::id() }}">
                    <input type="hidden" name="SessId" id="editSessId">
                    <input type="hidden" name="CaseId" id="CaseId">

                    <div class="custom-modal-text ">
                        <div class="form-group">
                            <label>@lang('case.session_date') :</label>
                            <input type="text" autocomplete="off" id="editsessionDate" class="form-control"
                                name="sessionDate" placeholder="@lang('case.session_date_placeholder')"
                                data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_time'):</label>
                            <input type="time" id="editsessionTime" value="16:04" autocomplete="off" class="form-control"
                                name="sessionTime" placeholder="@lang('case.session_time_placeholder')"
                                data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_zoom_id') :</label>
                            <input type="text" id="editzoomId" class="form-control" name="zoomId"
                                placeholder="@lang('case.session_zoom_id_placeholder')" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_note'):</label>
                            <textarea class="form-control" id="editnote" name="note"
                                placeholder="@lang('case.session_note_placeholder')"></textarea>
                        </div>
                        <span>@lang('case.session_party'):</span>
                        <div class="form-group" id="editsessionParty">
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn-sm btn mt-3  btn-secondary"
                                data-dismiss="modal">Close</button>
                            <input type="submit" name="@lang('case.session_add_title')"
                                class="btn-sm btn btn-primary mt-3">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="Session-delete-meditor" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel1"
        aria-hidden="true" class="modal-demo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Delete Session</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
                    </button>
                </div>

                <form method="post">

                    <input type="hidden" name="createdBy" id="deletecreatedByF" value="{{ Auth::id() }}">
                    <input type="hidden" name="deSessId" id="deleteSessId">

                    <div class="custom-modal-text ">

                        <div class="form-group">
                            <label>Reason for Delete</label>
                            <textarea class="form-control" id="reasondelete" name="reasondelete" placeholder="Reason for Delete"></textarea>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn-sm btn mt-3  btn-secondary"
                                data-dismiss="modal">Close</button>
                            <input type="button" id="sessiondeleteform" name="deleteSession"
                                class="btn-sm btn btn-primary mt-3" value="Submit">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="Session-delete-reason" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel1"
        aria-hidden="true" class="modal-demo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">View Reason</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
                    </button>
                </div>

                <div class="modal-body" style="background-color: darkgray; color: white;" id="view_reason"></div>

            </div>
        </div>
    </div>
@endsection

<!-- Table datatable css -->
@section('head')
    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
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
        $(function() {
            $("#sessionDate").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy'
            });
            $("#sessionDateForBulk").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy'
            });

        });
    </script>
    <script>
        $.validate();
    </script>
    <script>
        function convertTime12to24(time12h) {
            const [time, modifier] = time12h.split(' ');

            let [hours, minutes] = time.split(':');

            if (hours === '12') {
                hours = '00';
            }
            if (hours.length == 1) {
                hours = '0' + hours;
            }

            if (modifier === 'PM') {
                hours = parseInt(hours, 10) + 12;
            }

            return `${hours}:${minutes}`;
        }

        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }

        var userTable = $('#users').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('mediator.case.jsonOngoing', $confirm_status) }}',
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000],
            ],
            "iDisplayLength": 25,
            "columns": [{
                    "data": "case.id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "case",
                    render: function(data, type, row) {
                        var button = "";
                        button = button + `<input type="checkbox" class="blkchk" data-caseid="` + data.id +
                            `">`;
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data) {
                        return "M" + pad(data, 6);
                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "case.id",
                    render: function(data) {
                        var button = "";
                        button = button + `<a href="{{ url('mediator/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        button = button + `<a href="{{ url('mediator/consent-and-disclosures/') }}/` +
                            data +
                            `" target="_blank" class="btn btn-teal waves-light waves-effect btn-sm">Disclosure</a> `;
                        return button;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";
                        for (i in data) {
                            if (data[i].userId != 0) {
                                if (data[i].name != null) {
                                    if (data[i].organization != null && data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .organization + `</span><br>`;
                                    } else {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    }
                                }
                            } else {
                                if (data[i].name != null) {
                                    d = d + `<span class="text-danger party_name" data-inid="` + data[i]
                                        .id + `" data-id="` + data[i].userId + `">` + data[i].name +
                                        `</span><br>`;
                                }
                            }
                        }
                        return d;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = "";
                        button = button +
                            `<div class="position-relative"> <button type="button"  data-type="1" data-typename="Private" data-id="` +
                            data +
                            `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">Private</button>`;
                        button += ` <span class="badge-success badge private_total">` + row.private_count +
                            `</span>`;
                        if (row.private_view_count != 0) {
                            button += ` <span class="badge badge-danger private_unseen">` + row
                                .private_view_count + `</span>`;
                        }
                        button = button +
                            ` <button type="button" data-type="0" data-typename="Share" data-id="` + data +
                            `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark waves-effect btn-sm">Share</button>`;
                        if (row.share_view_count !== 0) {
                            button += ` <span class="badge  badge-danger share_unseen">` + row
                                .share_view_count + ` </span>`;
                        }
                        button += ` <span class="badge badge-success share_total">` + row.share_count +
                            `</span></div>`;
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = "";
                        button = button + ` <button id="Sessview` + data + `" value="` + data +
                            `"  data-id="` + data +
                            `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal"  ><span class="mdi mdi-file-eye-outline"></span></button>`;
                        button = button + ` <button value="` + data + `"  data-id="` + data +
                            `"   class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target="#addSession-modal" ><span class="mdi mdi-pencil-plus"></span></button>`;
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = "";
                        button = button + ` <button value="` + data + `"  data-id="` + data +
                            `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">Upload Supporting</button>`;
                        button = button + ` <button value="` + data + `"  data-id="` + data +
                            `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-teal waves-light waves-effect btn-sm">Close</button>`;
                        return button;
                    }
                },
            ],
        });

        $("#selectalldir").change(function() {
            if (this.checked) {
                $("#bulkCloseBtn").show();
                $("#bulkUpload").show();
                $("#bulkSession").show();

                $(".blkchk").each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $(".blkchk").each(function() {
                    $(this).prop("checked", false);
                });
                $("#bulkCloseBtn").hide();
                $("#bulkUpload").hide();
                $("#bulkSession").hide();

            }
        });

        $(document).on("change", ".blkchk", function() {
            var case_count = 0;
            $(".blkchk").each(function() {
                if (this.checked) {
                    case_count++;
                }
            });
            if (this.checked) {
                $("#bulkCloseBtn").show();
                $("#bulkUpload").show();
                $("#bulkSession").show();

            } else {
                if (case_count == 0) {

                    $("#bulkCloseBtn").hide();
                    $("#bulkUpload").hide();
                    $("#bulkSession").hide();
                }
            }
            if ($('#selectalldir').is(':checked')) {
                $("#bulkAcceptBtn").show();
                $("#bulkRejectBtn").show();
            }
            if (case_count == 0) {
                $("#bulkAcceptBtn").hide();
                $("#bulkRejectBtn").hide();
                $("#selectalldir").prop("checked", false);
            }
        });
        $('#addSessionFormForBulk').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];
            var count = 0;
            $(".blkchk").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);
            swal({
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal + " Cases are selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete) {
                if (willDelete) {
                    $(".blkchk").each(function() {
                        if (this.checked) {
                            var id = $(this).data("caseid");
                            $('#addSessionFormForBulk').find('input[name="caseId"]').val(id);
                            $.ajax({
                                type: 'post',
                                url: '{{ route('mediator.addSession') }}',
                                data: $('#addSessionFormForBulk').serialize(),
                                beforeSend: function() {
                                    $('#addSession-modal').modal("hide");

                                    swal({
                                        title: 'Loading...',
                                        showConfirmButton: false,
                                        buttons: false,
                                        allowOutsideClick: false,
                                    });
                                },
                                success: function() {
                                    // alert('form was submitted');
                                    swal("session created!", {
                                        icon: "success",
                                    }).then(function() {
                                        location.reload();
                                    });
                                    $('#addSession-modal').modal("hide");
                                }
                            });
                        }

                    });
                } else {
                    swal("@lang('case.request_canseled')");
                }
            });

        });
        $('#withdrawFormForBulk').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];
            var count = 0;
            $(".blkchk").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);
            swal({
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal + " Cases selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $(".blkchk").each(function() {
                        if (this.checked) {
                            var id = $(this).data("caseid");
                            $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]')
                                .val(id);

                            $.ajax({
                                type: 'post',
                                url: '{{ route('mediator.case.withdraw') }}',
                                data: $('#withdrawFormForBulk').serialize(),
                                beforeSend: function() {
                                    swal({
                                        title: 'Loading...',
                                        showConfirmButton: false,
                                        buttons: false,

                                    });
                                },
                                success: function() {
                                    // alert('form was submitted');
                                    userTable.ajax.reload(null, false);
                                    swal("@lang('case.status_change_successfully')", {
                                        icon: "success",
                                    }).then(function() {
                                        location.reload();
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

        $('#uploadFormModalForBulk').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];

            var count = 0;
            $(".blkchk").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);
            var formData = new FormData(this);
            let TotalFiles = $('#filesForBulk')[0].files.length;
            let files = $('#filesForBulk')[0];
            for (let i = 0; i < TotalFiles; i++) {
                formData.append('files' + i, files.files[i]);
            }
            formData.append('TotalFiles', TotalFiles);
            swal({
                title: "Are you sure?",
                text: withdrawcountTotal + " Cases selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete) {
                if (willDelete) {
                    $(".blkchk").each(function() {
                        if (this.checked) {
                            formData.delete('caseId');
                            var id = $(this).data("caseid");
                            // $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]').val(id);
                            formData.append('caseId', id);

                            $.ajax({
                                type: 'POST',
                                url: '{{ route('mediator.storeMultiFile') }}',
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                beforeSend: function() {
                                    // $('#uploadSupportingDocsModal').modal("hide");

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
                                    }).then(function() {
                                        location.reload();
                                    });
                                    $("#uploadSupportingDocsModalForBulk").modal(
                                        "hide");
                                },
                                error: function(data) {
                                    //alert(data.responseJSON.errors.files[0]);
                                    console.log(data);
                                }
                            });
                        }

                    });
                } else {
                    swal("Cancel Confirm Request!");
                }
            });

        });

        $(document).on('submit', "#MidaterForm", function() {
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
                        url: '{{ route('admin.case.midater_add') }}',
                        method: "post",
                        data: {
                            id: id,
                            midater: midater,
                            '_token': csrf
                        },
                        beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,

                            });
                        },
                    }).done(function(data) {
                        swal("mediator assigned successfully!", {
                            icon: "success",
                        }).then(function() {
                            location.reload();
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
        $('#multi-file-upload-ajax').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            let TotalFiles = $('#files')[0].files.length;
            let files = $('#files')[0];
            let party = [];
            $("input:checkbox[name=docs_party_ids]:checked").each(function() {
                party.push($(this).val());
            });
            for (let i = 0; i < TotalFiles; i++) {
                formData.append('files' + i, files.files[i]);
            }
            formData.append('TotalFiles', TotalFiles);
            formData.append('docs_party_ids', party);
            $.ajax({
                type: 'POST',
                url: '{{ route('mediator.storeMultiFile') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    // $('#uploadSupportingDocsModal').modal("hide");

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
                    }).then(function() {
                        location.reload();
                    });
                    $("#uploadSupportingDocsModal").modal("hide");
                },
                error: function(data) {
                    //alert(data.responseJSON.errors.files[0]);
                    console.log(data);
                }
            });
        });

        $('#withdrawModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var modal = $(this)
            modal.find('.modal-body input[name="case_id"]').val(recipient);
        });
        $('#commentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var typename = button.data('typename');
            var type = button.data('type');
            var modal = $(this);
            var urlpdf = '{{ route('mediator.case.commentPDF', '', '') }}' + '/' + id + '/' + type;

            $("#commentView").html("");
            $('#commentForm .modal-footer #DownLoadPdf').remove();
            $.ajax({
                type: 'post',
                url: '{{ route('mediator.case.comment_view') }}',
                data: {
                    type: type,
                    case_id: id
                },
                success: function(data) {
                    for (i in data) {
                        //console.log(data[0]);
                        if (data[i].username == '{{ Auth::user()->username }}') {
                            var msg = `<div class="col-md-12 text-right border-top">
                            <div class="row">
                                            <div class="col-md-4 text-left"><small class="text-muted">` + data[i]
                                .created + `</small></div>
                                            <div class="col-md-8"><small class="text-muted">` + data[i].username + `</small></div>
                                </div>
                                 <p>` + data[i].comment + `</p>
                        </div>`;
                            $("#commentView").append(msg);
                        } else {
                            var msg = `<div class="col-md-12 border-top">
                            <div class="row">
                                            <div class="col-md-8"><small class="text-muted">` + data[i].username + `</small></div>
                                            <div class="col-md-4 text-right"><small class="text-muted">` + data[i]
                                .created + `</small></div>
                                </div>
                                 <p>` + data[i].comment + `</p>
                        </div>`;
                            $("#commentView").append(msg);
                        }
                    }
                    if (data[i] != null) {
                        $('#commentForm .modal-footer').append("<a href=" + urlpdf +
                            "><button type='button' class='btn btn-success' id='DownLoadPdf'>Download Comment</button></a>"
                        );
                    }
                }
            });

            modal.find('#commentModalLabel').text(typename);
            modal.find('.modal-body input[name="type"]').val(type);
            modal.find('.modal-body input[name="case_id"]').val(id);
        });

        $('#commentModal-close').on('click', function() {
            userTable.ajax.reload(null, false);
        });

        $('#commentForm').on('submit', function(e) {
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
                        url: '{{ route('mediator.case.comment') }}',
                        data: $('#commentForm').serialize(),
                        success: function() {
                            $('#commentForm')[0].reset();
                            swal("comment save successfully!", {
                                icon: "success",
                            }).then(function() {
                                // location.reload();
                            });
                            $('#commentModal').modal("hide");
                        }
                    });
                } else {
                    swal("comment not added!");
                }
                userTable.ajax.reload(null, false);

            });
            return false;
        });
        $('#withdrawForm').on('submit', function(e) {
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
                        url: '{{ route('mediator.case.withdraw') }}',
                        data: $('#withdrawForm').serialize(),
                        beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,

                            });
                        },
                        success: function() {
                            // alert('form was submitted');
                            userTable.ajax.reload();
                            swal("status change successfully!", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
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
        $('#Session-delete-reason').on('show.bs.modal', function(event) {
            $("#view_reason").text("");
            var button = $(event.relatedTarget);
            var reason = button.data('reason');
            $("#view_reason").text(reason);
        });
        $('#midaterAdd').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var mediator = button.data('mediator');
            var modal = $(this)
            modal.find('.modal-body input[name="id"]').val(recipient);
            modal.find('.modal-body select[name="midater"]').val(mediator);
        });
        $('#uploadSupportingDocsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var data = button.parent().parent().find(".party_name");
            $("#PartyDocs").html("");
            data.each(function() {
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
                url: '{{ route('mediator.viewSupporting') }}',
                data: {
                    id: recipient
                },
                success: function(data) {
                    $("#supportingDocumnet tbody").html('');
                    $("#supportingDocumnet tbody").append(data);
                    //$("#supportingDocumnet").datatable();
                }
            });
            $('#caseIdF1').val(recipient);
        });

        $('#addSession-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var data = button.parent().parent().find(".party_name");
            $("#sessionParty").html("");
            data.each(function() {
                var party_id = $(this).data("inid")
                var party_name = $(this).text()
                var text = `<div class="form-group form-check">
                <input type="checkbox" value="` + party_id +
                    `" class="form-check-input" name="session_party_ids[]" id="party` + party_id + `" data-validation="checkbox_group" data-validation-qty="min1">
                <label class="form-check-label" for="party` + party_id + `">` + party_name + `</label>
              </div>`;
                $("#sessionParty").append(text);
            });
            var recipient = button.data('id');
            var mediator = button.data('mediator');
            $('#caseIdF').val(recipient);
        });
        $('#addSessionForm').on('submit', function(e) {

            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{{ route('mediator.case.addSession') }}',
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
                success: function() {
                    // alert('form was submitted');
                    swal("session created!", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                    $('#addSession-modal').modal("hide");
                }
            });
        });
        $('#viewSession-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var caseid = button.data('id')
            var sheduledBy_Id = '{{ Auth::id() }}';
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            $.ajax({
                type: 'post',
                url: '{{ route('mediator.case.getAddedSesion') }}',
                data: {
                    mediator_id: sheduledBy_Id,
                    caseid: caseid,
                    '_token': csrf
                },
                success: function(data) {
                    var pdfButton = "";
                    if (data != "") {
                        // console.log(caseid);
                        var link = '{{ route('mediator.case.sessionPdf', '') }}' + '/' + caseid;
                        // console.log(link);
                        pdfButton = "<a target='_blank' href='" + link +
                            "' class='btn btn-success'><span>Download PDF</span></button>"
                        $('#sessRecId tbody').html(data);
                        $('#sessionShowBtn').html(pdfButton);
                    } else {
                        $('#sessRecId tbody').html("No Session");
                        $('#sessionShowBtn').html(pdfButton);

                    }
                    // $('#sessRecId tbody').html(data);
                }

            });
        });

        $('#Session-edit-mediator').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            $("#editsessionParty").html("");
            var editId = button.data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('mediator.SendforEditSession') }}",
                data: {
                    id: editId
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.hasOwnProperty('id')) {
                        var data = $('#Sessview' + response.case_id).parent().parent().find(
                            '.party_name');

                        $('#editSessId').attr('value', response.id);
                        $('#CaseId').attr('value', response.case_id);
                        $('#editsessionDate').attr('value', response.session_date.substring(0, 10));
                        var convertTime = convertTime12to24(response.session_date.substring(11));
                        $('#editsessionTime').attr('value', convertTime);
                        $('#editzoomId').attr('value', response.zoom_id);
                        $('#editnote').val(response.note);
                        var session_party = response.session_party_ids;
                        data.each(function() {
                            var party_id = $(this).data("inid")
                            console.log(party_id);

                            var user_id = $(this).data("id");
                            var party_name = $(this).text()

                            var text = `<div class="form-check">`;
                            if (session_party.includes(party_id) || session_party.includes(
                                    user_id)) {
                                // console.log("if",party_id);
                                // console.log("if",user_id);
                                text = text + `<input type="checkbox" checked value="` +
                                    party_id +
                                    `" class="form-check-input" name="session_party_ids[]" id="party` +
                                    party_id + `" data-validation="checkbox_group" data-validation-qty="min1">
                                <label class="form-check-label" for="party` + party_id + `">` + party_name +
                                    `</label>`;


                            } else {
                                // console.log("else",party_id);
                                // console.log("else",user_id);
                                text = text + `<input type="checkbox" value="` + party_id +
                                    `" class="form-check-input" name="session_party_ids[]" id="party` +
                                    party_id + `" data-validation="checkbox_group" data-validation-qty="min1">
                                <label class="form-check-label" for="party` + party_id + `">` + party_name +
                                    `</label>`;
                            }
                            text = text + `</div>`;
                            $("#editsessionParty").append(text);
                        });
                    } else {
                        console.log('response Not Found');
                    }

                }
            });
        });

        $("#UpdateSessionForm").on('submit', function(e) {
            e.preventDefault();
            var formSet = $('#UpdateSessionForm').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('mediator.UpdateSession') }}",
                data: formSet,
                dataType: "JSON",
                beforeSend: function() {
                    $('#viewSession-modal').modal("hide");

                    swal({
                        title: 'Loading...',
                        showConfirmButton: false,
                        buttons: false,
                        allowOutsideClick: false,
                    });
                },
                success: function(response) {
                    $('#Session-edit-mediator').modal('hide');
                    swal("Session Updated Successfully!", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                }
            });
        })
        $('#Session-delete-meditor').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var delid = button.data('id');
            $("#deleteSessId").val(delid);
        });
        $(document).on('click', '#sessiondeleteform', function() {
            var Sessid = $("#deleteSessId").val();
            var reason = $("#reasondelete").val();
            swal({
                title: "Are you sure?",
                text: "Delete this Session!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('mediator.DeleteSession') }}",
                        data: {
                            SessId: Sessid,
                            reason: reason
                        },
                        dataType: "JSON",
                        success: function(response) {

                            swal({
                                title: "Deleted Successfully!",
                                // text: "Delete this Session!",
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
