@extends('admin.layouts.app')
@section('title', 'Close Request')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.closed_request')</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Close Request')


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <div class="row">
                    <div class="col-md-4">
                        <select name="batch" id="batchSelect" class="form-control">
                            <option value="" selected>Select Batch...</option>
                            @foreach ($batchName as $value)
                                <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                            @endforeach

                        </select>
                        <br>
                        <br>
                    </div>
                </div>
                <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>@lang('case.serial_number')</th>
                            <th>Select</th>
                            <th>@lang('case.case_id')</th>
                            <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                    data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                        class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                    data-original-title="Click here to view the 'Request for Mediation'."><i
                                        class="fa fa-info-circle" aria-hidden="true"></i></a> </th>
                            <th>@lang('case.party_details')</th>
                            <th>@lang('case.mediator')</th>
                            <th>@lang('case.comment') <a href="#" data-toggle="tooltip" title="" data-original-title="Private comments are for internal reference only. Shared comments are visible to the appointed Mediator. Comments are not visible to the parties.
     "><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            <th>@lang('case.session') <a href="#" data-toggle="tooltip" title=""
                                    data-original-title="Schedule meeting date and time. Parties will be notified via email."><i
                                        class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            <th>@lang('case.settlement_agreement')</th>
                            <th>@lang('case.status_logs') <a href="#" data-toggle="tooltip" title=""
                                    data-original-title="Current status of the Mediation appears here."><i
                                        class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        </tr>
                    </thead>
                </table>
                <div class="row">
                    <div class="col-md-2">

                        <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input
                                type="checkbox" id="selectalldir"> Select All Cases</label>

                    </div>
                    <div class="col-md-4">
                        <button class="blkbtn btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#uploadSupportingDocsModalForBulk" id="bulkUpload"
                            style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload Supporting
                            Documents</button>
                    </div>

                </div>
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

    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">@lang('case.title_status_change')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="withdrawForm" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="message-text"
                                class="col-form-label">@lang('case.textarea_title_status_change')</label>
                            <textarea class="form-control" name="withdraw_comment" readonly></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('case.status_change_btn_close')</button>
                    </div>
                </form>
            </div>
        </div>
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
                        <input type="hidden" name="case_id" class="form-control">
                        <input type="hidden" name="type" class="form-control">

                        <div class="form-group">
                            <label for="message-text"
                                class="col-form-label">@lang('case.share_privet_comment_textarea')</label>
                            <textarea class="form-control" name="comment" required></textarea>
                        </div>
                        <div class="row" id="commentView" style="height: 200px;overflow-x: auto">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="commentModal-close"
                            data-dismiss="modal">@lang('case.share_privet_btn_close')</button>
                        <button type="submit" class="btn btn-primary">@lang('case.share_privet_btn_save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade h-75" id="viewSession-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                            <th scope="col">@lang('case.session_serial_number')</th>
                            <th scope="col">@lang('case.session_date')</th>
                            <th scope="col">@lang('case.session_time')</th>
                            <th scope="col">@lang('case.session_zoom_id')</th>
                            <th scope="col">@lang('case.session_note')</th>
                            <th scope="col">@lang('case.session_meeting_user')</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
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
                    <h5 class="modal-title" id="exampleModalLabel">@lang('case.session_add_title')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                </div>

                <form id="addSessionForm">

                    <input type="hidden" name="createdBy" id="createdByF" value="{{ Auth::id() }}">
                    <input type="hidden" name="caseId" id="caseIdF" value="">

                    <div class="custom-modal-text ">

                        <span>@lang('case.session_date')</span>
                        <input type="text" autocomplete="off" id="sessionDate" class="form-control" name="sessionDate"
                            placeholder="@lang('case.session_date_placeholder')">

                        <span>@lang('case.session_time')</span>
                        <input type="time" id="sessionTime" autocomplete="off" class="form-control" name="sessionTime"
                            placeholder="@lang('case.session_time_placeholder')">

                        <span>@lang('case.session_zoom_id')</span>
                        <input type="text" id="zoomId" class="form-control" name="zoomId"
                            placeholder="@lang('case.session_zoom_id_placeholder')">

                        <span>@lang('case.session_note')</span>
                        <textarea class="form-control" id="note" name="note"
                            placeholder="@lang('case.session_note_placeholder')"></textarea>

                        <div class="text-center">
                            <input type="submit" name="addSession" class="btn-sm btn-primary mt-3">
                        </div>
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
                    <h4 class="modal-title text-white">@lang('case.supporting_title')</h4>
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
                        <div id="file_select"></div>
                        <br><br>
                        <div id="mediatorDocs"></div>
                        <br>
                        <span>Share With @lang('case.session_party'):</span>
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
                                <th>@lang('case.supporting_file')</th>
                                <th>@lang('case.supporting_upload_by')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>@lang('case.supporting_close')</span>
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
                    <h4 class="modal-title text-white">@lang('case.settlement_title')</h4>
                    <!-- <h5 class="modal-title mt-0">Last Session Records</h5> -->
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="settelmentForm" method="POST" action="javascript:void(0)" accept-charset="utf-8"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="caseId" id="caseIdF2" value="">
                        <input type="file" name="Settelmentfiles[]" id="Settelmentfiles" class="dropify"
                            data-height="150" multiple />
                        <br>
                        <input type="submit" id="submit" name="addSupportingDocs" class="btn-sm btn-primary mt-3">
                        <br>
                        <br>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('case.settlement_serial_number')</th>
                                <th>@lang('case.settlement_file')</th>
                                <th>@lang('case.settlement_upload_by')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>@lang('case.settlement_close')</span>
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
    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection


@section('footer')



    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
    <script src="{{ url('/') }}/assets/js/sweetalert.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#sessionDate").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy'
            });
        });
    </script>

    <script>
        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
        var batch_id;

        $("#batchSelect").change(function() {
            batch_id = $("#batchSelect :selected").val();
            userTable.ajax.reload(null, false);
        })
        var userTable = $('#users').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('admin.case.json', $confirm_status) }}',
            "processing": true,
            "serverSide": true,
            "order": [[2, "desc"]],

            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000],
            ],
            "iDisplayLength": 10,
            "responsive": true,
            serverData: function(sSource, aoData, fnCallback, oSettings) {
                // aoData.append('token',token)
                aoData.push({
                    name: "batch_id",
                    value: batch_id
                });
                oSettings = $.ajax({
                    dataType: "json",
                    type: "post",
                    // async: false,
                    crossDomain: true,
                    url: sSource,
                    data: aoData,
                    success: fnCallback

                });

            },

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
                        var button = "M" + pad(data, 6);
                        button = button + `<br><a href="{{ url('admin/track/') }}/` + data +
                            `" target="_blank" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a> `
                        return button;
                    }
                },
                {
                    "data": "date"
                },
                {
                    "data": "case.id",
                    render: function(data) {
                        var button = ` <a href="{{ url('admin/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        return button;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";
                        for (i in data) {
                            if (data[i].isOnboarded == 1) {
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
                    "data": "case.mediator_username",
                    render: function(data, type, row) {
                        var button = "";
                        button = button + `<button value="` + row.case.id + `"  data-id="` + row.case.id +
                            `" class="btn btn-info btn-sm disabled" disabled>` + data + ` </button>`;
                        if (row.case.mediator_status == 0) {
                            button = button +
                                `<br><span class="badge badge-warning mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">pending</span> <br> `;
                        } else if (row.case.mediator_status == 1) {
                            // var date = new Date(row.mediator_status.updated_at);
                            //button = button + `<br><span class="badge badge-success">Accepted</span> <br> `;
                            button = button +
                                ` <br><a href="{{ url('admin/consent-and-disclosures/') }}/` + row.case
                                .id +
                                `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                            button = button +
                                `<br><span class="badge badge-success mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">Date of Consent: ` + row
                                .mediator_create_action_date + `</span>`;


                        }
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = "";
                        button = button +
                            `<div class="position-relative"> <button type="button"  data-type="1" data-typename="Private" data-id="` +
                            data +
                            `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">@lang('case.btn_private')</button>`;
                        button += ` <span class="badge-success badge private_total">` + row.private_count +
                            `</span>`;
                        if (row.private_view_count != 0) {
                            button += ` <span class="badge badge-danger private_unseen">` + row
                                .private_view_count + `</span>`;
                        }
                        button = button +
                            ` <button type="button" data-type="0" data-typename="Share" data-id="` + data +
                            `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark waves-effect btn-sm">@lang('case.btn_share')</button>`;
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
                        button = button + ` <button value="` + data + `"  data-id="` + data +
                            `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal"  ><span class="mdi mdi-file-eye-outline"></span></button>`;
                        return button;
                    }
                },
                {
                    "data": "case.withdraw",
                    render: function(data, type, row) {
                        var button = "";
                        //console.log(data);
                        if (row.status_log.length != 0 && row.status_log[0].status ==
                            '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}') {
                            button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case
                                .id +
                                `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                            button = button + ` <button value="` + data + `"  data-withdraw="` + data +
                                `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-success waves-effect btn-sm">@lang('case.btn_withdrawn')</button>`;
                        } else if (row.status_log.length != 0 && row.status_log[0].status ==
                            '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                            button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case
                                .id +
                                `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                            button = button + ` <button value="` + data + `"  data-withdraw="` + data +
                                `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-danger waves-effect btn-sm">@lang('case.btn_unresolved')</button>`;
                        } else {
                            button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case
                                .id +
                                `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                            button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case
                                .id +
                                `" class="btn btn-success waves-effect btn-sm" data-toggle="modal" data-target="#settelmentModal">@lang('case.btn_view_settelment')</button>`;
                        }
                        return button;
                    }
                },
                {
                    "data": "status_log",
                    render: function(data, type, row) {
                        var button = "";
                        for (i in data) {
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_ADMIN }}' || data[
                                    i].status == '{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}'
                                ) {
                                button = button + `<span class="badge badge-success">` + data[i]
                                    .description + ` | At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}') {
                                button = button + `<span class="badge badge-info ">` + data[i].description +
                                    ` | At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' || data[
                                    i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' || data[i]
                                .status == '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                                button = button + `<span class="badge badge-danger">` + data[i]
                                    .description + ` | At : ` + data[i].created + `</span><br>`;
                            }
                        }
                        return button;
                    }
                },
            ],
        });

        $("#selectalldir").change(function() {
            if (this.checked) {
                $("#bulkUpload").show();
                $(".blkchk").each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $(".blkchk").each(function() {
                    $(this).prop("checked", false);
                });
                $("#bulkUpload").hide();
            }
        });

        $(document).on("change", ".blkchk", function() {
            var case_count = 0;
            $(".blkchk").each(function() {
                if (this.checked) {
                    case_count++;
                }
                if (this.checked) {
                    $("#bulkUpload").show();
                } else {
                    if (case_count == 0) {
                        $("#bulkUpload").hide();
                    }
                }
                if ($('#selectalldir').is(':checked')) {
                    $("#bulkUpload").show();
                }
                if (case_count == 0) {
                    $("#bulkUpload").hide();
                    $("#selectalldir").prop("checked", false);
                }
            });
        })

        $('#settelmentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            $.ajax({
                type: 'post',
                url: '{{ route('admin.case.viewSettelment') }}',
                data: {
                    id: recipient
                },
                success: function(data) {
                    $("#settelmentModal tbody").html('');
                    $("#settelmentModal tbody").append(data);
                    //$("#supportingDocumnet").datatable();
                }
            });
            $('#caseIdF2').val(recipient);
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
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal.toString() + " Cases selected",
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
                                url: '{{ route('admin.case.storeMultiFile') }}',
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                beforeSend: function() {
                                    $('#uploadSupportingDocsModalForBulk').modal(
                                    "hide");

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
                    swal("@lang('case.request_canseled')");
                }
            });

        });

        $('#uploadSupportingDocsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var data = button.parent().parent().find(".party_name");
            var mediatorData = button.parent().parent().find(".mediator_action");
            // console.log(mediatorData);

            $("#PartyDocs").html("");
            $("#mediatorDocs").html("");
            $("#file_select").html("");
            var fileSelect =
                `<input type="file" name="files[]" id="files" class="dropify" data-height="150" multiple required />`;
            $("#file_select").append(fileSelect);
            mediatorData.each(function() {
                var action = $(this).data("mediatoraction");
                var mtext = "";
                if (action == 1) {
                    mtext = `<label>Share With Mediator?</label><input class="ml-2" type="radio" name="shareMediator" id="shareYes" checked value="1">Yes
                            <input class="ml-2" type="radio" name="shareMediator" id="shareNo" value="0">No`;
                } else {
                    mtext =
                        `<input class="ml-2 d-none" type="radio" name="shareMediator" id="shareNo" checked value="0">`;
                }
                $("#mediatorDocs").append(mtext);
                // console.log(mtext);
            });
            data.each(function() {
                var party_id = $(this).data("inid")
                var party_name = $(this).text()
                var text = `<div class="form-check">
                <input type="checkbox" value="` + party_id + `" class="form-check-input" name="docs_party_ids" id="party">
                <label class="form-check-label" for="party">` + party_name + `</label>
              </div>`;
                $("#PartyDocs").append(text);
            });
            $.ajax({
                type: 'post',
                url: '{{ route('admin.case.viewSupporting') }}',
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
        $('#multi-file-upload-ajax').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            let TotalFiles = $('#files')[0].files.length;
            let files = $('#files')[0];
            let party = [];
            $("input:checkbox[name=docs_party_ids]:checked").each(function() {
                party.push($(this).val());
            });
            $("input:radio[name=shareMediator]:checked").each(function() {
                shareMediator = $(this).val();
            });
            for (let i = 0; i < TotalFiles; i++) {
                formData.append('files' + i, files.files[i]);
            }


            formData.append('TotalFiles', TotalFiles);
            formData.append('docs_party_ids', party);
            formData.append('shareMediator', shareMediator);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.case.storeMultiFile') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function() {
                    $('#uploadSupportingDocsModal').modal("hide");

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
                    // $("#uploadSupportingDocsModal").modal("hide");
                },
                error: function(data) {
                    //alert(data.responseJSON.errors.files[0]);
                    console.log(data);
                }
            });
        });
        $('#settelmentForm').on('submit', function(e) {
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
                url: '{{ route('admin.case.settelmen_upload') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function() {
                    // alert('form was submitted');
                    swal("@lang('case.settelment_has_been_uploaded')", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                    $("#settelmentModal").modal("hide");
                    userTable.ajax.reload(null, false);
                }
            });
        });
        $('#commentModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var typename = button.data('typename');
            var type = button.data('type');
            var modal = $(this)
            var urlpdf = '{{ route('admin.case.commentPDF', '', '') }}' + '/' + id + '/' + type;

            $("#commentView").html("");
            $('#commentForm .modal-footer #DownLoadPdf').remove();

            $.ajax({
                type: 'post',
                url: '{{ route('admin.case.comment_view') }}',
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
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.add_this_comment')",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('admin.case.comment') }}',
                        data: $('#commentForm').serialize(),
                        success: function() {
                            swal("@lang('case.comment_save_successfully')", {
                                icon: "success",
                            }).then(function() {});
                            $('#commentForm')[0].reset();
                            $('#commentModal').modal("hide");
                        }
                    });
                } else {
                    swal("@lang('case.comment_not_added')");
                }
                userTable.ajax.reload(null, false);

            });
            return false;
        });
        $('#withdrawModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var withdraw = button.data('withdraw');
            var modal = $(this)
            modal.find('.modal-body textarea[name="withdraw_comment"]').text(withdraw);
        });
        $('#withdrawForm').on('submit', function(e) {
            e.preventDefault();
            swal({
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.withdraw_this_request')",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'post',
                        url: '{{ route('admin.case.withdraw') }}',
                        data: $('#withdrawForm').serialize(),
                        success: function() {
                            // alert('form was submitted');
                            userTable.ajax.reload(null, false);
                            swal("@lang('case.status_change_successfully')", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
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
        $('#addSession-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var mediator = button.data('mediator');
            $('#caseIdF').val(recipient);
        });
        $('#addSessionForm').on('submit', function(e) {

            e.preventDefault();
            $.ajax({
                type: 'post',
                url: '{{ route('admin.case.addSession') }}',
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
                url: '{{ route('admin.case.getAddedSesion') }}',
                data: {
                    mediator_id: sheduledBy_Id,
                    caseid: caseid,
                    '_token': csrf
                },
                success: function(data) {
                    var pdfButton = "";
                    if (data != "") {
                        // console.log(caseid);
                        var link = '{{ route('admin.case.sessionPdf', '') }}' + '/' + caseid;
                        // console.log(link);
                        pdfButton = "<a target='_blank' href='" + link +
                            "' class='btn btn-success'><span>Download PDF</span></button>"
                        $('#sessRecId tbody').html(data);
                        $('#sessionShowBtn').html(pdfButton);
                    } else {
                        $('#sessRecId tbody').html("No Session");
                        $('#sessionShowBtn').html(pdfButton);

                    }
                }

            });
        });
    </script>
@endsection
