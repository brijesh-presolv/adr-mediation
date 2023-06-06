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
<style>
    table tbody .btn,  table tbody td{
        font-size: 14px;
    }
    table tbody button {
        margin-top: 7px;
    }
    table tbody input[type='checkbox'] {
        margin: 15px;
        height: 12px;
    }
</style>

    <section class="tabs-section">
        <div>
            <button class="btn btn-sm btn-primary mr-3" data-target="#myModalbupldCourierAdmin" data-toggle="modal"> Bulk
                Courier .csv</button>
            <button class="btn btn-sm btn-primary " data-target="#myModalbupldCourierzipAdmin" data-toggle="modal"> Bulk
                Courier .zip</button>
            <br>
            <br>
        </div>
        <div id="myModalbupldCourierAdmin" class="mdladcm modal fade " role="dialog" data-keyboard="false"
            data-backdrop="static">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- {{dd($allUsers)}} --}}
                        <div class="blkfrmdiv" style="width: 100%;">
                            <h3>Upload Courier .csv file</h3>
                            <form enctype="multipart/form-data" id="myModalbupldCourierAdminForm" method="post">
                                {{ csrf_field() }}

                                {{-- <input type="hidden" name="uploaded_by" value="{{auth()->user()->id}}" /> --}}
                                <div class="form-group">
                                    <input type="text" name="type" id="type" placeholder="Type"
                                        class="col-md-12 form-control" required="" />
                                </div>
                                <div class="form-group">
                                    <input type="file" name="csv" id="fileInput" onchange=""
                                        data-allowed-file-extensions="csv" class="col-md-12 dropify" required=""
                                        data-max-file-size="20M" />
                                </div>

                                <input type="Submit" value="Submit" class="btn btn-sm btn-primary blkupdbtnsb">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"
                                    aria-label="Close">
                                    <span>@lang('case.btn_close')</span>
                                </button>
                                <a href="/storage/app/public/courier_csv_sample/courier_sample.csv">Download Sample
                                    File.</a>

                            </form>

                        </div>


                    </div>
                </div>

            </div>
        </div>

        <div id="myModalbupldCourierzipAdmin" class="mdladcm modal fade " role="dialog" data-keyboard="false"
            data-backdrop="static">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- {{dd($allUsers)}} --}}
                        <div class="blkfrmdiv" style="width: 100%;">
                            <h3>Upload Courier .zip file</h3>
                            <p class="text-center">Upload a zip folder containing the PDF named as courier_caseid.pdf (eg:
                                courier_M003214.pdf, courier_M001234.pdf) and the PDF named as (eg: courier_M003214.pdf,
                                courier_M003214_1.pdf ... courier_M003214_20.pdf) for multiple address</p>
                            <form enctype="multipart/form-data" id="myModalbupldCourierzipAdminForm" method="post">
                                {{ csrf_field() }}

                                {{-- <input type="hidden" name="uploaded_by" value="{{auth()->user()->id}}" /> --}}
                                <div class="form-group">
                                    <input type="text" name="type" id="type" placeholder="Type"
                                        class="col-md-12 form-control" required="" />
                                </div>
                                <div class="form-group">
                                    <input type="file" name="zip" id="fileInput" onchange=""
                                        data-allowed-file-extensions="zip" class="col-md-12 dropify" required=""
                                        data-max-file-size="20M" />
                                </div>

                                <input type="Submit" value="Submit" class="btn btn-sm btn-primary blkupdbtnsb">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"
                                    aria-label="Close">
                                    <span>@lang('case.btn_close')</span>
                                </button>
                            </form>

                        </div>


                    </div>
                </div>

            </div>
        </div>
        <div class="tabs-section-nav">

            <div class="tbl">

                <ul class="nav" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tabs-2-tab-3" role="tab" data-toggle="tab" id="tab1">
                            <span class="nav-link-in">
                                Bulk Cases
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="#tabs-2-tab-1" role="tab" data-toggle="tab" id="tab2">
                            <span class="nav-link-in">
                                Individual Cases
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-3">
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
                    <table id="usersBulk" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <!-- <th>Select</th> -->
                                <th>@lang('case.case_id')</th>
                                <th>@lang('case.ref_id')</th>
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a> </th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('case.mediator')</th>
                                <!-- <th>@lang('case.comment') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Private comments are for internal reference only. Shared comments are visible to the appointed Mediator. Comments are not visible to the parties."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                             -->
                                <th>@lang('case.session') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Schedule meeting date and time. Parties will be notified via email."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>Documents</th>
                                <th>@lang('case.status_logs') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Current status of the Mediation appears here."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            </tr>
                        </thead>
                    </table>
                    <div class="row">
                        <div class="col-md-2">

                            <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input
                                    type="checkbox" id="selectalldirBulkcases"> Select All Cases</label>

                        </div>
                        <div class="col-md-4">
                            <button class="blkbtn btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#uploadSupportingDocsModalForBulk" id="bulkUploadBulkcases"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload
                                Supporting
                                Documents</button>
                        </div>

                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="tabs-2-tab-1">
                <div class="card-box table-responsive">
                    <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <!-- <th>Select</th> -->
                                <th>@lang('case.case_id')</th>
                                <!-- <th>@lang('case.ref_id')</th> -->
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a> </th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('case.mediator')</th>
                                <!-- <th>@lang('case.comment') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Private comments are for internal reference only. Shared comments are visible to the appointed Mediator. Comments are not visible to the parties."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                 -->
                                <th>@lang('case.session') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Schedule meeting date and time. Parties will be notified via email."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>Documents</th>
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
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload
                                Supporting
                                Documents</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

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
                        <input type="submit" id="submit" name="addSupportingDocs"
                            class="btn btn-sm btn-primary mt-3">
                        <br>
                        <br>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"
                        id="modelclose">
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
                            <label for="message-text" class="col-form-label">@lang('case.textarea_title_status_change')</label>
                            <textarea class="form-control" name="withdraw_comment" readonly></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.status_change_btn_close')</button>
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
                            <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea')</label>
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
                        <input type="text" autocomplete="off" id="sessionDate" class="form-control"
                            name="sessionDate" placeholder="@lang('case.session_date_placeholder')">

                        <span>@lang('case.session_time')</span>
                        <input type="time" id="sessionTime" autocomplete="off" class="form-control"
                            name="sessionTime" placeholder="@lang('case.session_time_placeholder')">

                        <span>@lang('case.session_zoom_id')</span>
                        <input type="text" id="zoomId" class="form-control" name="zoomId"
                            placeholder="@lang('case.session_zoom_id_placeholder')">

                        <span>@lang('case.session_note')</span>
                        <textarea class="form-control" id="note" name="note" placeholder="@lang('case.session_note_placeholder')"></textarea>

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
                        <input type="submit" id="submit" name="addSupportingDocs"
                            class="btn btn-sm btn-primary mt-3">
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
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close"
                        id="modelclose">
                        <span>@lang('case.supporting_close')</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="settelmentModal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
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

    {{-- modal for bulk cases send --}}
    <div class="row">
        <div class="col-md-12">
            <button style="display:none;" class="btn btn-sucess ccdd" data-target="#myModalcc"
                data-toggle="modal"></button>


            <div id="myModalcc" class="mdladcm modal fade " role="dialog" data-keyboard="false"
                data-backdrop="static">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header d-flex flex-column align-items-center">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <br>
                            <div class="loading_form text-center" style="display: none;">
                                {{-- <center> --}}
                                <p>Please Wait. Do Not Close Until Close Button Appear.</p>
                                <span id="loading_image">
                                    <img src="{{ url('assets/') }}/images/loading_form.gif">
                                </span>
                                {{-- </center> --}}


                                <!-- <div><a href="ongoing" class="btn btn-danger btn-lg directionCloseSwal" style="display: none;">Close</a></div> -->
                            </div>
                            <div id="totalPer"></div>
                            <div style='margin: auto; max-height: 100px; position:sticky; overflow-y:scroll;'
                                id="messcc">
                            </div>
                            <input type="hidden" id="last_uploaded_id" value="">
                            <div id="messccclose" style="margin-top: 2em;"></div>
                        </div>
                    </div>
                </div>
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
        $(function() {
            $("#sessionDate").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy'
            });
            $('.dropify').dropify();
        });
    </script>

    <script>
        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }
        var batch_id;

        var userTableBulk = $('#usersBulk').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('admin.case.json', [$confirm_status, 1]) }}',
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "order": [
                [2, "desc"]
            ],

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
                        var button = "";
                        button = button + `<input type="checkbox" class="blkchkBulkcases" data-caseid="` +
                            data.id +
                            `">`;
                        return meta.row + meta.settings._iDisplayStart + 1 + button;
                    }
                },
                // {
                //     "data": "case",
                //     render: function(data, type, row) {
                //         var button = "";
                //         button = button + `<input type="checkbox" class="blkchkBulkcases" data-caseid="` +
                //             data.id +
                //             `">`;
                //         return button;
                //     }
                // },
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
                    "data": "case.ref_id",
                    render: function(data, type, row, meta) {
                        if (data == null) {
                            var button = "";
                            button = button + `<p style="font-size: 16px;"> -- </p>`;
                            return button;
                        } else {
                            var button = "";
                            button = button + `<p style="font-size: 16px;">` + data + `</p>`;
                            return button;
                        }

                    }
                },
                {
                    "data": "date",
                    render: function(data, type, row) {
                        var button = `<p><b>Date of Creation</b><br>` + data +
                            `</p><p><b>Date of Approval</b><br>` + row.admin_approve + `</p>`;
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = ` <a href="{{ url('admin/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        
                        // Batch Name //
                        var batch =
                        `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Batch Name</p><p style="color: blue; font-size:13px;">` +
                        row.case.batch_name + `</p> </div>`;
                        // Batch Name //
                        
                        return button + batch;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";

                        var d_ip = "<strong>Initiating Party(s) :</strong><br/>";
                        var d_rp = "<br/><strong>Responding Party(s) :</strong><br/>";

                        for (i in data) {

                            if (data[i].isOnboarded == 1) {
                                var class_name = "text-success";
                            } else {
                                var class_name = "text-danger";
                            }

                            if (data[i].name != null && data[i].isClaimant == 0) {
                                d_ip = d_ip +
                                `<span class="party_name" data-inid="` +
                                data[
                                    i].id + `" data-id="` + data[i].userId +
                                `">` + data[i]
                                .name + `</span><br>`;

                            } else {
                                d_rp = d_rp +
                                `<span class="`+ class_name + ` party_name" data-inid="` +
                                data[i]
                                .id + `" data-id="` + data[i].userId + `">` + data[
                                    i].name +
                                `</span><br>`;
                            }

                            /*
                            if (data[i].isOnboarded == 1) {
                                if (data[i].name != null) {
                                    if (data[i].organization != null && data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .organization + `</span><br>`;

                                        
                                        var ip_name = data[i].name;
                                        if (ip_name != "") {
                                            d = d + `<span class="text-success" data-inid="` + data[
                                                    i].id + `" data-id="` + data[i].userId + `">` +
                                                ip_name + `</span><br>`;
                                        }
                                        
                                    } else {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    }
                                }
                            } else {
                                if (data[i].name != null) {
                                    // d = d + `<span class="text-danger party_name" data-inid="` + data[i]
                                    //     .id + `" data-id="` + data[i].userId + `">` + data[i].name +
                                    //     `</span><br>`;
                                    if (data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    } else {
                                        d = d + `<span class="text-danger party_name" data-inid="` + data[i]
                                            .id + `" data-id="` + data[i].userId + `">` + data[i].name +
                                            `</span><br>`;
                                    }
                                }
                            }
                            */
                        }
                        d = d + d_ip + d_rp;
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
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">pending</span> <br> `;
                        } else if (row.case.mediator_status == 1) {
                            // var date = new Date(row.mediator_status.updated_at);
                            //button = button + `<br><span class="badge badge-success">Accepted</span> <br> `;
                            button = button +
                                ` <br><a href="{{ url('admin/consent-and-disclosures/') }}/` + row.case
                                .id +
                                `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">Date of Consent: ` + row
                                .mediator_create_action_date + `</span>`;


                        }
                        return button;
                    }
                },
                // {
                //     "data": "case.id",
                //     render: function(data, type, row) {
                //         var button = "";
                //         button = button +
                //             `<div class="position-relative"> <button type="button"  data-type="1" data-typename="Private" data-id="` +
                //             data +
                //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">@lang('case.btn_private')</button>`;
                //         button += ` <span class="badge-success badge private_total">` + row.private_count +
                //             `</span>`;
                //         if (row.private_view_count != 0) {
                //             button += ` <span class="badge badge-danger private_unseen">` + row
                //                 .private_view_count + `</span>`;
                //         }
                //         button = button +
                //             ` <button type="button" data-type="0" data-typename="Share" data-id="` + data +
                //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark waves-effect btn-sm">@lang('case.btn_share')</button>`;
                //         if (row.share_view_count !== 0) {
                //             button += ` <span class="badge  badge-danger share_unseen">` + row
                //                 .share_view_count + ` </span>`;
                //         }
                //         button += ` <span class="badge badge-success share_total">` + row.share_count +
                //             `</span></div>`;
                //         return button;
                //     }
                // },
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
                            button = button + ` <br><button value="` + data + `"  data-withdraw="` + data +
                                `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-success waves-effect btn-sm">@lang('case.btn_withdrawn')</button>`;
                        } else if (row.status_log.length != 0 && row.status_log[0].status ==
                            '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                            button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case
                                .id +
                                `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                            button = button + ` <br><button value="` + data + `"  data-withdraw="` + data +
                                `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-danger waves-effect btn-sm">@lang('case.btn_unresolved')</button>`;
                        } else {
                            button = button + ` <button value="` + row.case.id + `"  data-id="` + row.case
                                .id +
                                `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                            button = button + ` <br><button value="` + row.case.id + `"  data-id="` + row.case
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
                                button = button + `<span class="">` + data[i]
                                    .description + ` <br> At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}') {
                                button = button + `<span class="">` + data[i].description +
                                    ` <br> At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' || data[
                                    i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' || data[i]
                                .status == '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                                button = button + `<span class="">` + data[i]
                                    .description + ` <br> At : ` + data[i].created + `</span><br>`;
                            }
                        }
                        return button;
                    }
                },
            ],
        });

        var userTable;

        $("a.nav-link").click(function() {
            // $("#filesForBulk").val('');
            $("#uploadFormModalForBulk").trigger("reset");
            $(".dropify-clear").click();
            if ($(this).attr("id") == "tab1") {
                userTableBulk.ajax.reload(null, false);
                $("#selectalldir, #selectalldirBulkcases, .blkchk, .blkchkBulkcases").prop("checked", false);
                $("#bulkUpload, #bulkUploadBulkcases")
                    .hide();

            }
            if ($(this).attr("id") == "tab2") {
                $("#uploadFormModalForBulk").trigger("reset");
                $(".dropify-clear").click();
                $("#selectalldirBulkcases, #selectalldir, .blkchk, .blkchkBulkcases").prop("checked", false);

                $("#bulkUpload, #bulkUploadBulkcases")
                    .hide();

                userTable = $('#users').DataTable({
                    "serverMethod": "POST",
                    "sAjaxSource": '{{ route('admin.case.json', [$confirm_status, 0]) }}',
                    "processing": true,
                    "serverSide": true,
                    "bDestroy": true,
                    "order": [
                        [2, "desc"]
                    ],

                    "lengthMenu": [
                        [10, 25, 50, 100, 250, 500, 1000],
                        [10, 25, 50, 100, 250, 500, 1000],
                    ],
                    "iDisplayLength": 10,
                    "responsive": true,
                    serverData: function(sSource, aoData, fnCallback, oSettings) {
                        // aoData.append('token',token)

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
                                var button = "";
                                button = button +
                                    `<input type="checkbox" class="blkchk" data-caseid="` + data
                                    .id +
                                    `">`;
                                return meta.row + meta.settings._iDisplayStart + 1 + button;
                            }
                        },
                        // {
                        //     "data": "case",
                        //     render: function(data, type, row) {
                        //         var button = "";
                        //         button = button +
                        //             `<input type="checkbox" class="blkchk" data-caseid="` + data
                        //             .id +
                        //             `">`;
                        //         return button;
                        //     }
                        // },
                        {
                            "data": "case.id",
                            render: function(data) {
                                var button = "M" + pad(data, 6);
                                button = button + `<br><a href="{{ url('admin/track/') }}/` +
                                    data +
                                    `" target="_blank" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a> `
                                return button;
                            }
                        },
                        // {
                        //     "data": "case.ref_id",
                        //     render: function(data, type, row, meta) {
                        //         if (data == null) {
                        //             var button = "";
                        //             button = button + `<p style="font-size: 16px;"> -- </p>`;
                        //             return button;
                        //         } else {
                        //             var button = "";
                        //             button = button + `<p style="font-size: 16px;">` + data + `</p>`;
                        //             return button;
                        //         }

                        //     }
                        // },
                        {
                            "data": "date",
                            render: function(data, type, row) {
                                var button = `<p><b>Date of Creation</b><br>` + data +
                                    `</p><p><b>Date of Approval</b><br>` + row.admin_approve + `</p>`;
                                return button;
                            }
                        },
                        {
                            "data": "case.id",
                            render: function(data, type, row) {
                                var button = ` <a href="{{ url('admin/casedetails/') }}/` +
                                    data +
                                    `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                                
                                // Batch Name //
                                // var batch =
                                // `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Batch Name</p><p style="color: blue; font-size:13px;">` +
                                // row.case.batch_name + `</p> </div>`;
                                // Batch Name //
                                
                                return button;
                            }
                        },
                        {
                            "data": "party",
                            render: function(data, type, row) {
                                var d = "";
                                var d_ip = "<strong>Initiating Party(s) :</strong><br/>";
                                var d_rp = "<br/><strong>Responding Party(s) :</strong><br/>";
                                for (i in data) {

                                    if (data[i].isOnboarded == 1) {
                                        var class_name = "text-success";
                                    } else {
                                        var class_name = "text-danger";
                                    }

                                    if (data[i].name != null && data[i].isClaimant == 0) {
                                        d_ip = d_ip +
                                                `<span class="party_name" data-inid="` +
                                                data[
                                                    i].id + `" data-id="` + data[i].userId +
                                                `">` + data[i]
                                                .name + `</span><br>`;
                                    } else {
                                        d_rp = d_rp +
                                                `<span class="`+ class_name + ` party_name" data-inid="` +
                                                data[i]
                                                .id + `" data-id="` + data[i].userId + `">` + data[
                                                    i].name +
                                                `</span><br>`;
                                    }

                                    /*
                                    if (data[i].isOnboarded == 1) {
                                        if (data[i].name != null) {
                                            if (data[i].organization != null && data[i]
                                                .isClaimant == 0) {
                                                d = d +
                                                    `<span class="text-success party_name" data-inid="` +
                                                    data[
                                                        i].id + `" data-id="` + data[i].userId +
                                                    `">` + data[i]
                                                    .organization + `</span><br>`;

                                              
                                                var ip_name = data[i].name;
                                                if (ip_name != "") {
                                                    d = d +
                                                        `<span class="text-success party_name" data-inid="` +
                                                        data[
                                                            i].id + `" data-id="` + data[i].userId +
                                                        `">` + ip_name + `</span><br>`;
                                                }
                                                
                                            } else {
                                                d = d +
                                                    `<span class="text-success party_name" data-inid="` +
                                                    data[
                                                        i].id + `" data-id="` + data[i].userId +
                                                    `">` + data[i]
                                                    .name + `</span><br>`;
                                            }
                                        }
                                    } else {
                                        if (data[i].name != null) {
                                            // d = d +
                                            //     `<span class="text-danger party_name" data-inid="` +
                                            //     data[i]
                                            //     .id + `" data-id="` + data[i].userId + `">` + data[
                                            //         i].name +
                                            //     `</span><br>`;
                                            if (data[i].isClaimant == 0) {
                                                d = d + `<span class="text-success party_name" data-inid="` +
                                                    data[
                                                        i].id + `" data-id="` + data[i].userId +
                                                    `">` + data[i].name + `</span><br>`;
                                            } else {
                                                d = d +
                                                    `<span class="text-danger party_name" data-inid="` +
                                                    data[i]
                                                    .id + `" data-id="` + data[i].userId + `">` +
                                                    data[i].name +
                                                    `</span><br>`;
                                            }
                                        }
                                    }
                                    */
                                }
                                d = d + d_ip + d_rp;
                                return d;
                            }
                        },
                        {
                            "data": "case.mediator_username",
                            render: function(data, type, row) {
                                var button = "";
                                button = button + `<button value="` + row.case.id + `"  data-id="` +
                                    row.case.id +
                                    `" class="btn btn-info btn-sm disabled" disabled>` + data +
                                    ` </button>`;
                                if (row.case.mediator_status == 0) {
                                    button = button +
                                        `<br><span class="mediator_action" data-mediatoraction="` +
                                        row.case.mediator_status + `">pending</span> <br> `;
                                } else if (row.case.mediator_status == 1) {
                                    // var date = new Date(row.mediator_status.updated_at);
                                    //button = button + `<br><span class="badge badge-success">Accepted</span> <br> `;
                                    button = button +
                                        ` <br><a href="{{ url('admin/consent-and-disclosures/') }}/` +
                                        row.case
                                        .id +
                                        `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                                    button = button +
                                        `<br><span class="mediator_action" data-mediatoraction="` +
                                        row.case.mediator_status + `">` + row
                                        .mediator_create_action_date + `</span>`;


                                }
                                return button;
                            }
                        },
                        // {
                        //     "data": "case.id",
                        //     render: function(data, type, row) {
                        //         var button = "";
                        //         button = button +
                        //             `<div class="position-relative"> <button type="button"  data-type="1" data-typename="Private" data-id="` +
                        //             data +
                        //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple waves-effect btn-sm">@lang('case.btn_private')</button>`;
                        //         button += ` <span class="badge-success badge private_total">` + row
                        //             .private_count +
                        //             `</span>`;
                        //         if (row.private_view_count != 0) {
                        //             button += ` <span class="badge badge-danger private_unseen">` +
                        //                 row
                        //                 .private_view_count + `</span>`;
                        //         }
                        //         button = button +
                        //             ` <button type="button" data-type="0" data-typename="Share" data-id="` +
                        //             data +
                        //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark waves-effect btn-sm">@lang('case.btn_share')</button>`;
                        //         if (row.share_view_count !== 0) {
                        //             button += ` <span class="badge  badge-danger share_unseen">` +
                        //                 row
                        //                 .share_view_count + ` </span>`;
                        //         }
                        //         button += ` <span class="badge badge-success share_total">` + row
                        //             .share_count +
                        //             `</span></div>`;
                        //         return button;
                        //     }
                        // },
                        {
                            "data": "case.id",
                            render: function(data, type, row) {
                                var button = "";
                                button = button + ` <button value="` + data + `"  data-id="` +
                                    data +
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
                                    button = button + ` <button value="` + row.case.id +
                                        `"  data-id="` + row.case
                                        .id +
                                        `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                                    button = button + ` <br><button value="` + data +
                                        `"  data-withdraw="` + data +
                                        `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-success waves-effect btn-sm">@lang('case.btn_withdrawn')</button>`;
                                } else if (row.status_log.length != 0 && row.status_log[0].status ==
                                    '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                                    button = button + ` <button value="` + row.case.id +
                                        `"  data-id="` + row.case
                                        .id +
                                        `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                                    button = button + `<br><button value="` + data +
                                        `"  data-withdraw="` + data +
                                        `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-danger waves-effect btn-sm">@lang('case.btn_unresolved')</button>`;
                                } else {
                                    button = button + ` <button value="` + row.case.id +
                                        `"  data-id="` + row.case
                                        .id +
                                        `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">@lang('case.btn_view_supporting')</button>`;
                                    button = button + ` <br><button value="` + row.case.id +
                                        `"  data-id="` + row.case
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
                                        '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_ADMIN }}' ||
                                        data[
                                            i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}'
                                    ) {
                                        button = button + `<span class="">` +
                                            data[i]
                                            .description + ` <br> At : ` + data[i].created +
                                            `</span><br>`;
                                    }
                                    if (data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}'
                                    ) {
                                        button = button + `<span class="">` + data[
                                                i].description +
                                            ` <br> At : ` + data[i].created + `</span><br>`;
                                    }
                                    if (data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' ||
                                        data[
                                            i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                        data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' ||
                                        data[i]
                                        .status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}'
                                    ) {
                                        button = button + `<span class="">` +
                                            data[i]
                                            .description + ` <br> At : ` + data[i].created +
                                            `</span><br>`;
                                    }
                                }
                                return button;
                            }
                        },
                    ],
                });
            }
        });


        $("#batchSelect").change(function() {
            batch_id = $("#batchSelect :selected").val();
            userTableBulk.ajax.reload(null, false);
        });

        $("#modelclose").click(function() {
            $("#uploadFormModalForBulk", "#multi-file-upload-ajax").trigger("reset");
            $(".dropify-clear").click();
        });

        var insertRow = false;
        var logId = null;
        var insId = null;
        var failId = null;

        var ite = [];
        var comp = 0;
        var prc = 0;
        var ajax_request_uploadDocs = function(item, url) {
            var formData = new FormData();
            formData.append("caseId", item.id);
            formData.append("total_row", item.total_row);
            formData.append("allcids", item.allcids);
            formData.append("log_id", logId);
            for (let i = 0; i < item.TotalFiles; i++) {
                formData.append("files" + i, item.fsData[i]);
            }
            formData.append("TotalFiles", item.TotalFiles);
            formData.append("insertRow", insId);
            formData.append("faildRow", failId);
            formData.append("token", item.token);

            var deferred = $.Deferred();

            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                contentType: false,
                processData: false,
                data: formData,
                cache: false,

                success: function(result) {

                    ite.push(result);
                    comp = comp + 1;
                    prc = Math.round(((comp * 100) / item.total_row));
                    $('#tto').html(prc);

                    if (logId == null) {
                        $("#loading_image").hide();
                        $("#totalPer").append(
                            "<h5 class='text-center mt-0'>Total " +
                            item.total_row +
                            " Case Selected, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5>"
                        );
                    }


                    // if (logId == null) {
                    //     //$(".loading_image").hide();
                    //     $("#blkform1_image").html(
                    //         "<center><h5>Total " +
                    //         item.total_row +
                    //         " Case Selected, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5></center>"
                    //     );
                    // }

                    logId = result.log_id;

                    if (result.response == "success") {
                        if (insId == null) {
                            insId = result.caseid;
                        } else {
                            insId = insId + "," + result.caseid;
                        }
                        $("#messcc").append(
                            "<p style='color: green;' class='text-center'>Case ID : M" +
                            result.caseid.toString().padStart(6, "0") + " Success.</p>"
                        );
                        // $("#mess").append(
                        //     "<center>Case Id A00" + result.caseid + " Success.</center>"
                        // );
                    } else {
                        if (failId == null) {
                            failId = result.caseid;
                        } else {
                            failId = failId + "," + result.caseid;
                        }
                        $("#messcc").append(
                            "<p style='color: red;' class='text-center'>Case ID : M" +
                            result.caseid.toString().padStart(6, "0") + " Failed.</p>"
                        );
                        // $("#mess").append(
                        //     "<center>Case Id A00" + result.caseid + " Failed.</center>"
                        // );
                    }
                    var objDiv = document.getElementById("messcc");
                    objDiv.scrollTop = objDiv.scrollHeight;
                    // var objDiv = document.getElementById("mess");
                    // objDiv.scrollTop = objDiv.scrollHeight;
                    deferred.resolve(result);
                },
                error: function(error) {
                    if (failId == null) {
                        failId = item.id;
                    } else {
                        failId = failId + "," + item.id;
                    }
                    $("#messcc").append(
                        "<center style='color: red;'>Case ID : M" +
                        item.cid.toString().padStart(6, "0") + " Failed.</center>"
                    );
                    // $("#mess").append(
                    //     "<center>Case Id A00" + error.caseid + " Failed.</center>"
                    // );
                    var objDiv = document.getElementById("messcc");
                    objDiv.scrollTop = objDiv.scrollHeight;
                    deferred.reject(error);
                },
                complete: function() {
                    swal.close();
                },
            });
            return deferred.promise();
        };

        var looper = $.Deferred().resolve();

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

        $("#selectalldirBulkcases").change(function() {
            if (this.checked) {
                $("#bulkUploadBulkcases").show();
                $(".blkchkBulkcases").each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $(".blkchkBulkcases").each(function() {
                    $(this).prop("checked", false);
                });
                $("#bulkUploadBulkcases").hide();
            }
        });

        $(document).on("change", ".blkchkBulkcases", function() {
            var case_count = 0;
            $(".blkchkBulkcases").each(function() {
                if (this.checked) {
                    case_count++;
                }
                if (this.checked) {
                    $("#bulkUploadBulkcases").show();
                } else {
                    if (case_count == 0) {
                        $("#bulkUploadBulkcases").hide();
                    }
                }
                if ($('#selectalldirBulkcases').is(':checked')) {
                    $("#bulkUploadBulkcases").show();
                }
                if (case_count == 0) {
                    $("#bulkUploadBulkcases").hide();
                    $("#selectalldirBulkcases").prop("checked", false);
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
            $(".blkchk, .blkchkBulkcases").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);
            var formData = new FormData(this);
            let TotalFiles = $('#filesForBulk')[0].files.length;
            let files = $('#filesForBulk')[0];
            var filesall = [];

            for (let i = 0; i < TotalFiles; i++) {
                formData.append('files' + i, files.files[i]);
                filesall[i] = files.files[i];

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
                    formData.delete('caseId');
                    var id = $(this).data("caseid");
                    // $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]').val(id);
                    formData.append('caseId', id);

                    var cids = null;
                    var idarr = [];
                    var ctcnt = 0;

                    $(".blkchk, .blkchkBulkcases").each(function() {
                        if (this.checked) {
                            ctcnt++;
                            if (cids == null) {
                                cids = $(this).data("caseid");
                            } else {
                                cids = cids + "," + $(this).data("caseid");
                            }
                        }
                    });

                    $(".blkchk, .blkchkBulkcases").each(function() {
                        if (this.checked) {
                            $('#uploadFormModalForBulk').find('.modal-body input[name="case_id"]')
                                .val(id);

                            // var fileobj = Object.assign({}, file);
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;

                            var file;

                            $.map($('#filesForBulk').get(0).files, function(file_m) {
                                file = file_m;
                            });
                            idarr.push({
                                token: csrf,
                                id: $(this).data("caseid"),
                                allcids: cids,
                                total_row: ctcnt,
                                fsData: filesall,
                                TotalFiles: TotalFiles,
                                log_type: "Upload-document",
                            });
                        }
                    });
                    var burl = "{{ route('admin.case.storeMultiFile') }}";
                    swal.close();
                    $(".ccdd").click();
                    $(".msgDiv").hide();
                    $(".loading_form").show();
                    $("#loading_image").show();
                    $(".close").hide();
                    $.when
                        .apply(
                            $,
                            $.map(idarr, function(item, i) {
                                looper = looper.then(function() {

                                    return ajax_request_uploadDocs(item, burl);

                                });
                                return looper;
                            })
                        )
                        .then(function() {
                            swal.close();
                            // $("#myModalcc").hide();
                            $("#messccclose").html("")
                            $("#messccclose").html(
                                '<br><center><a href="{{ route('admin.case.closedrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                    // $(".blkchk").each(function() {
                    //     if (this.checked) {
                    //         formData.delete('caseId');
                    //         var id = $(this).data("caseid");
                    //         // $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]').val(id);
                    //         formData.append('caseId', id);

                    //         $.ajax({
                    //             type: 'POST',
                    //             url: '{{ route('admin.case.storeMultiFile') }}',
                    //             data: formData,
                    //             cache: false,
                    //             contentType: false,
                    //             processData: false,
                    //             dataType: 'json',
                    //             beforeSend: function() {
                    //                 $('#uploadSupportingDocsModalForBulk').modal(
                    //                 "hide");

                    //                 swal({
                    //                     title: 'Loading...',
                    //                     showConfirmButton: false,
                    //                     buttons: false,
                    //                     allowOutsideClick: false,
                    //                 });
                    //             },
                    //             success: (data) => {
                    //                 //this.reset();
                    //                 swal("Files has been uploaded!", {
                    //                     icon: "success",
                    //                 }).then(function() {
                    //                     location.reload();
                    //                 });
                    //                 $("#uploadSupportingDocsModalForBulk").modal(
                    //                 "hide");
                    //             },
                    //             error: function(data) {
                    //                 //alert(data.responseJSON.errors.files[0]);
                    //                 console.log(data);
                    //             }
                    //         });
                    //     }

                    // });
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
            $('.dropify').dropify();
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

        $(document).on("click", ".secureDownload", function() {
            var id = $(this).data("id");
            var filename = $(this).data("url");
            var userid = $(this).data("userid");
            var parentFolder = $(this).data("folder");
            var csrf = document.querySelector('meta[name="csrf-token"]').content;

            $.ajax({
                url: '{{ route('downloadSecure') }}',
                method: "POST",
                data: {
                    id: id,
                    urlpath: filename,
                    parentFolder: parentFolder,
                    user_id: userid,
                    _token: csrf
                },
                xhrFields: {
                    responseType: "blob", // to avoid binary data being mangled on charset conversion
                },
                success: function(blob, status, xhr) {
                    // check for a filename
                    var filename = "";
                    var disposition = xhr.getResponseHeader("Content-Disposition");
                    if (disposition && disposition.indexOf("attachment") !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1])
                            filename = matches[1].replace(/['"]/g, "");
                    }

                    if (typeof window.navigator.msSaveBlob !== "undefined") {
                        // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var URL = window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) {
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");
                            // safari doesn't support this yet
                            if (typeof a.download === "undefined") {
                                window.location.href = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                            }
                        } else {
                            window.location.href = downloadUrl;
                        }

                        setTimeout(function() {
                            URL.revokeObjectURL(downloadUrl);
                            swal({
                                text: "Downloaded successfully!",
                                title: "Thanks!",
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                        }, 100); // cleanup
                    }
                },

                error: function(err) {
                    console.log(err);
                },
            });
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
                    if (typeof userTable !== "undefined") {
                        userTable.ajax.reload(null, false);
                    } else {
                        userTableBulk.ajax.reload(null, false);
                    }
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
            if (typeof userTable !== "undefined") {
                userTable.ajax.reload(null, false);
            } else {
                userTableBulk.ajax.reload(null, false);
            }
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
                        },
                        error: function(data) {
                            swal(data.responseJSON.errors.comment[0], {
                                icon: "error",
                            });
                        }
                    });
                } else {
                    swal("@lang('case.comment_not_added')");
                }
                if (typeof userTable !== "undefined") {
                    userTable.ajax.reload(null, false);
                } else {
                    userTableBulk.ajax.reload(null, false);
                }

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
                            if (typeof userTable !== "undefined") {
                                userTable.ajax.reload(null, false);
                            } else {
                                userTableBulk.ajax.reload(null, false);
                            }
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
        $(document).on('submit', '#myModalbupldCourierAdminForm', function(e) {
            e.preventDefault();
            // console.log("hello");
            var formdata = new FormData(this);

            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.case.CourierCSVUpload') }}",
                        data: formdata,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.type === "success") {
                                swal({
                                    title: "Upload Successfully!",
                                    // text: "Delete this Session!",
                                    icon: "success",
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: response.message,
                                    // text: "Delete this Session!",
                                    icon: "error",
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#myModalbupldCourierzipAdminForm', function(e) {
            e.preventDefault();
            // console.log("hello");
            var formdata = new FormData(this);

            swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.case.CourierZIPUpload') }}",
                        data: formdata,
                        dataType: "JSON",
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.type === "success") {
                                swal({
                                    title: "Upload Successfully!",
                                    // text: "Delete this Session!",
                                    icon: "success",
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                swal({
                                    title: response.message,
                                    // text: "Delete this Session!",
                                    icon: "error",
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        },
                    });
                }
            });

        });
    </script>
@endsection
