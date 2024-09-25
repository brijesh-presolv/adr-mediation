@extends('admin.layouts.app')
@section('title', 'New Request')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.new_request')</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'New Request')


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

        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-primary btn-sm" data-target="#myModalbupldAdmin" data-toggle="modal"> Bulk
                    Upload</button>
                <br>
                <br>
            </div>
            <div class="col-md-6 text-right">
                <select name="batch" id="batchSelectForApprove" class="form-control w-50 d-inline mr-2">
                    <option value="" selected>Select Batch...</option>
                    @foreach ($batchName as $value)
                        <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm text-center" data-toggle="modal"
                    data-target="#batchWiseMidaterAddForBulk" id="batchWiseApproveBtn"
                    data-arb="<?= Auth::user()->id ?>">Batch Wise Approve</button>
            </div>
        </div>
        <br><br>
        <div id="myModalbupldAdmin" class="mdladcm modal fade " role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- {{dd($allUsers)}} --}}
                        <div class="blkfrmdiv" style="width: 100%">
                            <h3>Upload .csv file</h3>
                            <form enctype="multipart/form-data" method="post" id="bulkUploadForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="token" id="token_input">
                                <div class="form-group">
                                    {{-- <label for="batch" class="col-md-5">Batch Name: </label> --}}
                                    <input type="text" name="batch" id="batch" placeholder="Batch Name (Optional)"
                                        class="col-md-12 form-control" />
                                </div>
                                <div class="form-group">
                                    <select class="form-control" name="claimant" id="claimant" required>
                                        <option value="">@lang('Select Claimant')</option>
                                        @if (isset($allUsers))
                                            @foreach ($allUsers as $value)
                                                @if ($value->isActive)
                                                    <option value="{{ $value->id }}">{{ $value->first_name }}
                                                        {{ $value->last_name }} - {{ $value->organization }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    {{-- <input type="hidden" name="claimant" value="{{auth()->user()->id}}"> --}}
                                </div>
                                {{-- <input type="hidden" name="uploaded_by" value="{{auth()->user()->id}}" /> --}}


                                <!------ Added for sub user listing --------------->
                                <div class="form-group">
                                    <select class="form-control" name="subuser" id="subuser">
                                    </select>
                                </div>
                                <!------ Added for sub user listing --------------->

                                <!--- ITM Language ---------------->
                                <!-- <div class="form-group">
                                    <label for="itm_lang">Select ITM Language</label>
                                    <select class="select2 form-control select2-multiple" multiple="multiple"
                                    data-placeholder="" name="itm_lang[]" name="itm_lang" id="itm_lang">
                                        <option value="">Select ITM Language</option>
                                        <option value="hindi">Hindi</option>
                                        <option value="marathi">Marathi</option>
                                        <option value="punjabi">Punjabi</option>
                                        <option value="tamil">Tamil</option>
                                        <option value="telugu">Telugu</option>
                                        <option value="malyalam">Malyalam</option>
                                        <option value="kannad">Kannad</option>
                                    </select>
                                </div> -->
                                <!--- ITM Language ---------------->

                                <div class="form-group">
                                    <input type="file" name="csv" id="fileInput" onchange=""
                                        class="col-md-12 dropify" data-allowed-file-extensions="csv" required=""
                                        data-max-file-size="500M" />
                                </div>

                                <input type="Submit" value="Submit" class="btn btn-primary blkupdbtnsb"
                                    id="bulkUploadForm">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                    <span>@lang('case.btn_close')</span>
                                </button>
                            </form>

                        </div>


                    </div>
                </div>

            </div>
        </div>

        <div id="documentUpload" class="mdladcm modal fade " role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- {{dd($allUsers)}} --}}
                        <div class="blkfrmdiv" style="width: 100%">
                            <h3>Upload Document</h3>
                            <form enctype="multipart/form-data" method="post" id="UploadDocumentForm">
                                {{ csrf_field() }}
                                <input type="hidden" name="caseid" class="form-control" id="recipientCaseid">

                                <div class="form-group">
                                    <input type="file" name="fileupload" id="fileInput" onchange=""
                                        class="col-md-12 dropify" data-allowed-file-extensions="pdf zip rar"
                                        required="" />
                                </div>

                                <input type="Submit" value="Submit" class="btn btn-primary blkupdbtnsb"
                                    id="UploadDocumentForm">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
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
                        <a class="nav-link active" href="#tabs-2-tab-3" role="tab" data-toggle="tab"
                            id="tab1">
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
        <!--.tabs-section-nav-->

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active show" id="tabs-2-tab-3">
                <div class="card-box table-responsive">

                    {{-- <div class="row">

                        <div class="col-md-6">
                            <select name="batch" id="batchSelect" class="form-control">
                                <option value="" selected>Select Batch...</option>
                                @foreach ($batchName as $value)
                                    <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                                @endforeach

                            </select>
                            <br>
                            <br>
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="col-md-2">

                            <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input
                                    type="checkbox" id="selectalldirbulk"> Select All Cases</label>

                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success btn-sm blkbtn" data-toggle="modal"
                                data-target="#midaterAddForBulk" id="bulkAcceptBtnBulk" data-bulk="bulk"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk
                                Approve</button>
                            <button class="btn btn-danger btn-sm blkbtn" id="bulkRejectBtnBulk"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk
                                Reject</button>
                        </div>


                    </div>
                    <br>

                    <table id="usersBulk" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                {{-- <th>Select</th> --}}
                                <th>@lang('case.case_id')</th>
                                <th>@lang('case.ref_id')</th>
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('Supporting Document') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to upload any document/s in relation to the case."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.action') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click 'Confirm' to register the Mediation (after assigning an mediator). Click 'Reject' to decline the Mediation."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade " id="tabs-2-tab-1">
                <div class="card-box table-responsive">
                    <div class="row">
                        <div class="col-md-2">

                            <label class="checkbox-inline" style="float: left;margin-right: 10px;margin-top:10px;"><input
                                    type="checkbox" id="selectalldir"> Select All Cases</label>

                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-success btn-sm blkbtn" data-toggle="modal"
                                data-target="#midaterAddForBulk" id="bulkAcceptBtn" data-bulk="ind"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk
                                Approve</button>
                            <button class="btn btn-danger btn-sm blkbtn" id="bulkRejectBtn"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk
                                Reject</button>
                        </div>


                    </div>
                    <br><br>
                    <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                {{-- <th>Select</th> --}}
                                <th>@lang('case.case_id')</th>
                                <!-- <th>@lang('case.ref_id')</th> -->
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('Supporting Document') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to upload any document/s in relation to the case."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.action') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click 'Confirm' to register the Mediation (after assigning an mediator). Click 'Reject' to decline the Mediation."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>




        </div>

    </section>

    <div class="modal fade" id="midaterAdd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('case.assign_mediator')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="MidaterForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id" class="form-control" id="recipient-name">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('case.form_mediator')</label>
                            <select class="form-control" name="midater" required>
                                <option value="">@lang('case.form_select_mediator')</option>
                                @foreach ($users as $user)
                                    @if ($user->isActive)
                                        <option value="{{ $user->id }}">{{ $user->first_name }}
                                            {{ $user->last_name }} -
                                            {{ $user->organization }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!---- Contact for discussion ------>
                        <div class="form-group discussion-section" style="display: none;">
                            <label for="discussion-text" class="col-form-label">Contact for discussion :</label>
                            <input type="text" name="contact_for_discussion" class="form-control" value="">
                        </div>
                        <!---- Contact for discussion ------>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                        <button type="submit" class="btn btn-primary">@lang('case.btn_accept')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="midaterAddForBulk" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('case.assign_mediator')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="MidaterFormForBulk" method="post">
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('case.form_mediator')</label>
                            <select class="form-control" name="midater" required>
                                <option value="">@lang('case.form_select_mediator')</option>
                                @foreach ($users as $user)
                                    @if ($user->isActive)
                                        <option value="{{ $user->id }}">{{ $user->first_name }}
                                            {{ $user->last_name }} - {{ $user->organization }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!---- Contact for discussion ------>
                        <div class="form-group discussion-section" style="display: none;">
                            <label for="discussion-text" class="col-form-label">Contact for discussion :</label>
                            <input type="text" name="contact_for_discussion" class="form-control" value="">
                        </div>
                        <!---- Contact for discussion ------>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                        <button type="submit" class="btn btn-primary">@lang('case.btn_accept')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="batchWiseMidaterAddForBulk" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('case.assign_mediator')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- <form id="BatchWiseMidaterFormForBulk" method="post"> --}}
                <div class="modal-body">

                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('case.form_mediator')</label>
                        <select class="form-control" id="BatchWiseMidaterSelect" name="midater" required>
                            <option value="">@lang('case.form_select_mediator')</option>
                            @foreach ($users as $user)
                                @if ($user->isActive)
                                    <option value="{{ $user->id }}">{{ $user->first_name }}
                                        {{ $user->last_name }} - {{ $user->organization }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('case.btn_close')</button>
                    <button type="button" id="BatchWiseMidaterFormForBulk"
                        class="btn btn-primary">@lang('case.btn_accept')</button>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>

    <button style="display:none;" type="button" class="btn btn-info btn-lg cc1" data-toggle="modal"
        data-target="#msg1">MSG</button>
    <!-- message -->
    <div id="msg1" class="mdladcm modal fade " role="dialog" data-keyboard="false" data-backdrop="static">

        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">

                    <br>
                    <div class="msgDiv">

                    </div>
                    <div class="loading_form text-center" style="display: none;">
                        {{-- <center> --}}

                        {{-- </center> --}}
                        <p>Please Wait. Do Not Close Until Close Button Appear.</p>

                        <div style="height: 200px;
                                            overflow-y: scroll;"
                            id="mess">

                        </div>
                        <!-- <a>Close</a> -->
                    </div>

                </div>
            </div>

        </div>
    </div>

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

    <style>
        .select2-container {
            width: 100% !important;
        }
    </style>

    <link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ url('assets/') }}/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('assets/') }}/libs/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('footer')
    <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->

    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script src="{{ url('assets/') }}/libs/select2/select2.min.js"></script>
    <script src="{{ url('assets/') }}/libs/bootstrap-select/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
            $('#claimant').select2();
            $('#subuser').select2();
            $('#itm_lang').select2();
        });
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
                [10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
                [10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
            ],
            "iDisplayLength": 25,
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
            "columns": [

                {
                    "data": "key",
                    render: function(data, type, row) {
                        var button = `<span style="margin-right: 1em;">` + data + `</span>`;
                        if (row.party.length == 0) {
                            button = button +
                                `<input type="checkbox" disabled class="blkchkbulk" id="blkchkbulk" data-caseid="` +
                                row.case
                                .id +
                                `">`;
                        } else {
                            button = button +
                                `<input type="checkbox" class="blkchkbulk" id="blkchkbulk" data-caseid="` +
                                row.case
                                .id +
                                `">`;
                        }

                        return button;
                    }
                },
                // {
                //     "data": "case",
                //     render: function(data, type, row) {
                //         var button = "";
                //         if (row.party.length == 0) {
                //             button = button +
                //                 `<input type="checkbox" disabled class="blkchkbulk" id="blkchkbulk" data-caseid="` +
                //                 data
                //                 .id +
                //                 `">`;
                //         } else {
                //             button = button +
                //                 `<input type="checkbox" class="blkchkbulk" id="blkchkbulk" data-caseid="` +
                //                 data
                //                 .id +
                //                 `">`;
                //         }

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
                    "data": "date"
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {

                       //console.log(row);
                        var d = '';

                        if (row.party.length == 0) {
                            d = "disabled";
                        }
                        var button = ` <a href="{{ url('admin/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm ` +
                            d +
                            `" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        button = button + ` <a href="{{ url('admin/updatecase/') }}/` + data +
                            `" target="_blank" class="btn btn-info waves-effect waves-light btn-sm ` + d +
                            `" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                        
                        // Batch Name //
                        var batch =
                        `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Batch Name </p><p style="color: blue; font-size:13px;">` +
                        row.case.batch_name + `</p> </div>`;
                        // Batch Name //

                        if(row.sub_user != ""){
                            var sub_user = `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Sub User</p><p style="color: blue; font-size:13px;">` +
                        row.sub_user + `</p> </div>`;
                        } else {
                            var sub_user = ""; 
                        }
                        
                        
                        return button + batch + sub_user;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";

                        var d_ip = "<strong>Initiating Party(s) :</strong><br/>";
                        var d_rp = "<br/><strong>Responding Party(s) :</strong><br/>";

                        for (i in data) {
                            // console.log(data[i].documentPath);
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
                                if(data[i].name != null){
                                    d_rp = d_rp +
                                            `<span class="`+ class_name + ` party_name" data-inid="` +
                                            data[i]
                                            .id + `" data-id="` + data[i].userId + `">` + data[
                                                i].name +
                                            `</span><br>`;
                                }
                            }


                            /*
                            if (data[i].isOnboarded == 1) {
                                if (data[i].name != null) {
                                    if (data[i].organization != null && data[i].isClaimant == 0) {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .organization + `</span><br>`;
                                            
                                        
                                        var ip_name = data[i].name;
                                        if(ip_name != ""){
                                            d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + ip_name + `</span><br>`;
                                        }
                                        
                                    } else {
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                            .name + `</span><br>`;
                                    }
                                }
                            } else {
                                if (data[i].name != null) {
                                    // d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                                    if(data[i].isClaimant == 0){
                                        d = d + `<span class="text-success party_name" data-inid="` + data[
                                                i].id + `" data-id="` + data[i].userId + `">` + data[i].name + `</span><br>`;
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

                        if (d == '') {

                            return `<span class="text-danger">@lang('case.status_pending')</span><br>`;
                        }
                        return d;
                    }
                },
                {
                    "data": "case",
                    render: function(data, type, row) {
                        var d = "";
                        // for (i in data) {

                        if (data.documentPath != 'NULL' && data.documentPath != '' && data.documentPath !=
                            null) {
                            // d = d + `<p  class="btn btn-success btn-sm">` + data.documentPath + `</p>`;
                            d = d + `<a href='javascript:void(0);'  data-folder='user/supportingDocument'
                                data-url='` + data.documentPath + `'
                                data-id='` + data.id + `'
                                class='btn btn-success btn-sm secureDownload' 
                                >View</a>`;
                        } else {
                            d = d +
                                `<button class="btn btn-primary btn-sm" data-id="` + data.id +
                                `" data-target="#documentUpload" id="fordocumentupload" data-toggle="modal">Upload</button>`;
                            // d = d + `<form action="{{ url('admin/uploaddocument/') }}/` + data.id + `"  method="post" enctype="multipart/form-data">
                        //                 @csrf
                        //                 @method('PUT')
                        //                 <input  class="form-control dropify" type="file" id="document" name="document" data-allowed-file-extensions="pdf zip rar"  data-max-file-size="20M"></input>
                        //                 <p>*Only Pdf zip and rar file allowed</p>
                        //                 <input type="submit" class="btn btn-primary btn-sm" id="upload" value="Upload">
                        //                 </form>`;
                        }
                        // }


                        return d;
                    }
                },

                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        //  /console.log(row.case.discussion);
                        // return false;
                        var d = '';

                        if (row.party.length == 0) {
                            return button = "@lang('case.na')";
                        }

                        var button = "";
                        button = button + `<button value="` + data + `"  data-id="` + data +
                            `" data-toggle="modal" data-target="#midaterAdd" data-bulk="` + row.case
                            .bulk_flag + `" class="btn btn-info ` + d +
                            `">Approve</button>`;
                        button = button + ` <button value="` + data + `" class="btn btn-danger reject ` +
                            d + `">@lang('case.btn_reject')</button>`;
                        return button;
                    }
                },
            ],
        });

        var userTable;

        $("a.nav-link").click(function() {

            if ($(this).attr("id") == "tab1") {
                userTableBulk.ajax.reload(null, false);
                $("#selectalldir, #selectalldirbulk").prop("checked", false);
                $("#bulkAcceptBtnBulk, #bulkAcceptBtn").hide();
                $("#bulkRejectBtnBulk, #bulkRejectBtn").hide();
                $(".blkchk, .blkchkbulk").prop("checked", false);
            }
            if ($(this).attr("id") == "tab2") {
                $("#selectalldirbulk, #selectalldir").prop("checked", false);
                $("#bulkAcceptBtnBulk, #bulkAcceptBtn").hide();
                $("#bulkRejectBtnBulk, #bulkRejectBtn").hide();
                $(".blkchk, .blkchkbulk").prop("checked", false);
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
                        [10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
                        [10, 25, 50, 100, 250, 500, 1000, 2000, 5000],
                    ],
                    "iDisplayLength": 25,
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
                    "columns": [

                        {
                            "data": "key",
                            render: function(data, type, row) {
                                var button = `<span style="margin-right: 1em;">` + data + `</span>`;
                                if (row.party.length == 0) {
                                    button = button +
                                        `<input type="checkbox" disabled class="blkchk" id="blkchk" data-caseid="` +
                                        row.case
                                        .id +
                                        `">`;
                                } else {
                                    button = button +
                                        `<input type="checkbox" class="blkchk" id="blkchk" data-caseid="` +
                                        row.case
                                        .id +
                                        `">`;
                                }

                                return button;
                            }
                        },

                        // {
                        //     "data": "case",
                        //     render: function(data, type, row) {
                        //         var button = "";
                        //         if (row.party.length == 0) {
                        //             button = button +
                        //                 `<input type="checkbox" disabled class="blkchk" id="blkchk" data-caseid="` +
                        //                 data
                        //                 .id +
                        //                 `">`;
                        //         } else {
                        //             button = button +
                        //                 `<input type="checkbox" class="blkchk" id="blkchk" data-caseid="` +
                        //                 data
                        //                 .id +
                        //                 `">`;
                        //         }
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
                            "data": "date"
                        },
                        {
                            "data": "case.id",
                            render: function(data, type, row) {
                                var d = '';

                                if (row.party.length == 0) {
                                    d = "disabled";
                                }
                                var button = ` <a href="{{ url('admin/casedetails/') }}/` +
                                    data +
                                    `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm ` +
                                    d +
                                    `" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                                button = button + ` <a href="{{ url('admin/updatecase/') }}/` +
                                    data +
                                    `" target="_blank" class="btn btn-info waves-effect waves-light btn-sm ` +
                                    d +
                                    `" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                                
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
                                    // console.log(data[i].documentPath);
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
                                        if(data[i].name != null){
                                            d_rp = d_rp +
                                            `<span class="`+ class_name + ` party_name" data-inid="` +
                                            data[i]
                                            .id + `" data-id="` + data[i].userId + `">` + data[
                                                i].name +
                                            `</span><br>`;
                                        }
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
                                                        `<span class="text-success" data-inid="` +
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
                                            // d = d + `<span class="text-danger">` + data[i].name +
                                            //     `</span><br>`;
                                            if (data[i].isClaimant == 0) {
                                                d = d + `<span class="text-success" data-inid="` +
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
                                if (d == '') {

                                    return `<span class="text-danger">@lang('case.status_pending')</span><br>`;
                                }
                                return d;
                            }
                        },
                        {
                            "data": "case",
                            render: function(data, type, row) {
                                var d = "";
                                // for (i in data) {

                                if (data.documentPath != 'NULL' && data.documentPath != '' && data
                                    .documentPath !=
                                    null) {
                                    d = d + `<a href='javascript:void(0);'  data-folder='user/supportingDocument'
                                        data-url='` + data.documentPath + `'
                                        data-id='` + data.id + `'
                                        class='btn btn-success btn-sm secureDownload' 
                                        >View</a>`;
                                } else {
                                    d = d +
                                        `<button class="btn btn-primary btn-sm" data-id="` + data
                                        .id +
                                        `" data-target="#documentUpload" id="fordocumentupload" data-toggle="modal">Upload</button>`;
                                    // d = d + `<form action="{{ url('admin/uploaddocument/') }}/` +
                                    //     data.id + `"  method="post" enctype="multipart/form-data">
                                //         @csrf
                                //         @method('PUT')
                                //         <input  class="form-control dropify" type="file" id="document" name="document" data-allowed-file-extensions="pdf zip rar"  data-max-file-size="20M"></input>
                                //         <p>*Only Pdf zip and rar file allowed</p>
                                //         <input type="submit" class="btn btn-primary btn-sm" id="upload" value="Upload">
                                //         </form>`;
                                }
                                // }


                                return d;
                            }
                        },

                        {
                            "data": "case.id",
                            render: function(data, type, row) {

                                var d = '';

                                if (row.party.length == 0) {
                                    return button = "@lang('case.na')";
                                }

                                var button = "";
                                button = button + `<button value="` + data + `"  data-id="` + data +
                                    `" data-toggle="modal" data-target="#midaterAdd" data-bulk="` +
                                    row.case.bulk_flag + `" class="btn btn-info ` +
                                    d +
                                    `">Approve</button>`;
                                button = button + ` <button value="` + data +
                                    `" class="btn btn-danger reject ` +
                                    d + `">@lang('case.btn_reject')</button>`;
                                return button;
                            }
                        },
                    ],
                });
            }
        });

        // var batchdata = "{{ $batchName }}";
        var batchdata = {!! json_encode($batchName->toArray()) !!};

        // console.log(batchdata);

        var selectBatchOption = '<div class="ml-3" style="display: inline-flex; width: 50%;">' +
            '<select name="batch" id="batchSelect" class="form-control">' +
            '<option value="" selected>Select Batch...</option>';
        batchdata.map(e => {
            // console.log(e);
            selectBatchOption += '<option value=' + e.id + '>' + e.batch_name + '</option>';
        });
        selectBatchOption += '</select></div>';

        $(selectBatchOption).appendTo("#usersBulk_wrapper .dataTables_filter");


        function pad(str, max) {
            str = str.toString();
            return str.length < max ? pad("0" + str, max) : str;
        }


        $("#batchSelect").change(function() {
            batch_id = $("#batchSelect :selected").val();
            userTableBulk.ajax.reload(null, false);
        });


        var insertRow = false;
        var logId = null;
        var insId = null;
        var failId = null;

        var ite = [];
        var comp = 0;
        var prc = 0;

        var ajax_request = function(item, url) {
            var deferred = $.Deferred();
            console.log(item)
            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                data: {
                    id: item.id,
                    _token: item.token,
                    allcids: item.allcids,
                    total_row: item.total_row,
                    midater: item.midater,
                    log_id: logId,
                    insertRow: insId,
                    faildRow: failId,
                    log_type: item.log_type,
                },
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
                            //console.log('insId '+insId);
                        } else {
                            insId = insId + "," + result.caseid;
                            //console.log('insId d '+insId);
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

        var ajax_request_approve = function(item, mediator_url, confirm_url) {
            var deferred = $.Deferred();
            console.log(item)
            $.ajax({
                url: mediator_url,
                dataType: "json",
                type: "POST",
                data: {
                    id: item.id,
                    _token: item.token,
                    // allcids: item.allcids,
                    // total_row: item.total_row,
                    midater: item.midater,
                    // insertRow: insId,
                    // faildRow: failId,
                    // log_type: item.log_type,
                },
                success: function(result) {
                    $.ajax({
                        url: confirm_url,
                        dataType: "json",
                        type: "POST",
                        data: {
                            id: item.id,
                            _token: item.token,
                            allcids: item.allcids,
                            total_row: item.total_row,
                            log_id: logId,
                            insertRow: insId,
                            faildRow: failId,
                            log_type: item.log_type,
                        },
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
                                    " Case Selected, <span id='tto'><b>" + prc +
                                    "</span> %</b> Completed.</h5>"
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

                            console.log(result.log_id);
                            logId = result.log_id;



                            if (result.response == "success") {
                                if (insId == null) {
                                    insId = result.caseid;
                                    //console.log('insId '+insId);
                                } else {
                                    insId = insId + "," + result.caseid;
                                    //console.log('insId d '+insId);
                                }
                                $("#messcc").append(
                                    "<p style='color: green;' class='text-center'>Case ID : M" +
                                    result.caseid.toString().padStart(6, "0") +
                                    " Success.</p>"
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
                                    result.caseid.toString().padStart(6, "0") +
                                    " Failed.</p>"
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
                },
                // error: function (error) {
                //     if (failId == null) {
                //         failId = item.id;
                //     } else {
                //         failId = failId + "," + item.id;
                //     }
                //     $("#messcc").append(
                //         "<center style='color: red;'>Case ID : M" +
                //         item.cid.toString().padStart(6, "0") + " Failed.</center>"
                //     );
                //     // $("#mess").append(
                //     //     "<center>Case Id A00" + error.caseid + " Failed.</center>"
                //     // );
                //     var objDiv = document.getElementById("messcc");
                //     objDiv.scrollTop = objDiv.scrollHeight;
                //     deferred.reject(error);
                // },
                // complete: function () {
                //     swal.close();
                // },
            });
            return deferred.promise();
        };

        var countingcases;
        var notiId = null;

        var ajax_request_batch_wise = function(item, confirm_url) {
            var deferred = $.Deferred();
            $.ajax({
                url: confirm_url,
                dataType: "json",
                type: "POST",
                data: {
                    batch_id: item.batch_id,
                    _token: item.token,
                    logId: logId,
                    notiId: notiId,
                    // allcids: item.allcids,
                    total_row: item.total_row,
                    midater: item.mediator,
                    // insertRow: insId,
                    // faildRow: failId,
                    log_type: item.log_type,
                },
                success: function(result) {

                    // console.log(result);
                    var success = 0;
                    $("#loading_image").hide();
                    if (logId == null) {
                        $("#totalPer").append(
                            "<h5 class='text-center mt-0'>Total " +
                            countingcases +
                            " Cases, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5>"
                        );
                    }

                    logId = result.log_id;
                    notiId = result.notiId;


                    result.data.map((e) => {
                        var csrf = document.querySelector('meta[name="csrf-token"]').content;

                        $.ajax({
                            url: "{{ route('admin.case.batchwiseapprove') }}",
                            dataType: "json",
                            type: "POST",
                            data: {
                                id: e.id,
                                mediator: item.mediator,
                                logId: logId,
                                _token: csrf,
                            },
                            success: function(edata) {
                                success++;
                                if (edata.response == "success") {
                                    $("#messcc").append(
                                        "<p style='color: green;' class='text-center'>Case ID : M" +
                                        e.id.toString().padStart(6, "0") +
                                        " Success.</p>"
                                    );
                                    var objDiv = document.getElementById("messcc");
                                    objDiv.scrollTop = objDiv.scrollHeight;

                                    if (success === result.data.length) {
                                        comp = comp + result.data.length;
                                        prc = Math.round(((comp * 100) /
                                            countingcases));
                                        $('#tto').html(prc);
                                        deferred.resolve(result);
                                    }
                                } else {
                                    $("#messcc").append(
                                        "<p style='color: red;' class='text-center'>Case ID : M" +
                                        e.id.toString().padStart(6, "0") +
                                        " Fail.<br>" + edata.msg + "</p>"
                                    );
                                    if (success === result.data.length) {
                                        deferred.resolve(result);
                                    }
                                }
                            },
                            error: function(err) {
                                success++;
                                $("#messcc").append(
                                    "<p style='color: red;' class='text-center'>Case ID : M" +
                                    e.id.toString().padStart(6, "0") + " Fail.</p>"
                                );
                                if (success === result.data.length) {
                                    deferred.resolve(err);
                                }
                            }
                        });
                    })

                    // deferred.resolve(result);
                },
            });
            return deferred.promise();
        }

        var ajax_request_approve_with_midater_add = function(item, confirm_url) {
            var deferred = $.Deferred();
            // console.log(item.id.length);
            // for(var i = 0; i<)
            var complete = 0;
            // var 



            item.id.map((caseid) => {
                //console.log(item);
                if (typeof(item.discussion) != "undefined" && item.discussion !== null) {
                    var date_input = {
                        id: caseid,
                        mediator: item.midater,
                        discussion: item.discussion,
                        // logId: logId,
                        _token: item.token
                    }
                } else {
                    var data_input = {
                        id: caseid,
                        mediator: item.midater,
                        //discussion: item.discussion, 
                        // logId: logId,
                        _token: item.token
                    }
                }
                //alert(data_input);


                $.ajax({
                    url: "{{ route('admin.case.batchwiseapprove') }}",
                    dataType: "json",
                    type: "POST",
                    data: data_input,
                    success: function(result) {
                        complete++;
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
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;

                        } else {
                            if (failId == null) {
                                failId = result.caseid;
                            } else {
                                failId = failId + "," + result.caseid;
                            }
                            $("#messcc").append(
                                "<p style='color: red;' class='text-center'>Case ID : M" +
                                result.caseid.toString().padStart(6, "0") + " Fail.<br>" +
                                result.msg + "</p>"
                            );
                        }
                        if (complete === item.id.length) {
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;

                            // console.log("insId", insId);
                            // console.log("failId", failId);
                            // console.log("complete", complete);
                            // allcids: item.id,

                            $.ajax({
                                url: confirm_url,
                                dataType: "json",
                                type: "POST",
                                data: {
                                    // id: item.id,
                                    _token: csrf,
                                    allcids: item.allcids,
                                    total_row: item.total_row,
                                    logId: logId,
                                    notiId: notiId,
                                    insertRow: insId,
                                    faildRow: failId,
                                    log_type: item.log_type,
                                    mediator: item.midater,
                                },
                                success: function(data) {
                                    $("#loading_image").hide();
                                    if (logId == null) {
                                        $("#totalPer").append(
                                            "<h5 class='text-center mt-0'>Total " +
                                            item.total_row +
                                            " Cases, <span id='tto'><b>" + prc +
                                            "</span> %</b> Completed.</h5>"
                                        );
                                    }
                                    logId = data.log_id;
                                    notiId = data.notiId;

                                    comp = comp + item.id.length;
                                    prc = Math.round(((comp * 100) / item.total_row));
                                    $('#tto').html(prc);
                                    deferred.resolve(result);

                                },
                                error: function(err) {
                                    // deferred.resolve(err);
                                }
                            });
                        }
                    },
                    error: function(error) {
                        complete++;
                        $("#messcc").append(
                            "<p style='color: red;' class='text-center'>Case ID : M" +
                            caseid.toString().padStart(6, "0") + " Fail.</p>"
                        );
                        if (failId == null) {
                            failId = caseid;
                        } else {
                            failId = failId + "," + caseid;
                        }
                        // if(complete === item.id.length) {
                        //     var csrf = document.querySelector('meta[name="csrf-token"]').content;

                        //     // console.log("insId", insId);
                        //     // console.log("failId", failId);
                        //     // console.log("complete", complete);
                        //     // allcids: item.id,
                        //     $.ajax({
                        //         url: confirm_url,
                        //         dataType: "json",
                        //         type: "POST",
                        //         data: {
                        //             // id: item.id,
                        //             _token: csrf,
                        //             allcids: item.allcids,
                        //             total_row: item.total_row,
                        //             logId: logId,
                        //             notiId: notiId,
                        //             insertRow: insId,
                        //             faildRow: failId,
                        //             log_type: item.log_type,
                        //         },
                        //         success: function(data) {
                        //             $("#loading_image").hide();
                        //             if(logId == null) {
                        //                 $("#totalPer").append(
                        //                 "<h5 class='text-center mt-0'>Total " +
                        //                     item.total_row +
                        //                 " Cases, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5>"
                        //                 );
                        //             }
                        //             logId = result.log_id;
                        //             notiId = result.notiId;

                        //             comp = comp + item.id.length;
                        //             prc = Math.round(((comp * 100) / item.total_row));
                        //             $('#tto').html(prc); 
                        //             deferred.resolve(data);
                        //         },
                        //         error: function(err) {
                        //             deferred.resolve(err);
                        //         }
                        //     });
                        // }
                    }

                });
                console.log(complete);

            });

            // $.ajax({
            //     url: confirm_url,
            //     dataType: "json",
            //     type: "POST",
            //     data: {
            //         id: item.id,
            //         _token: item.token,
            //         allcids: item.id,
            //         total_row: item.total_row,
            //         logId: logId,
            //         midater : item.midater,
            //         notiId: notiId,
            //         // insertRow: insId,
            //         // faildRow: failId,
            //         log_type: item.log_type,
            //     },
            //     success: function(result) {
            //         // console.log(result);
            //         var success = 0;
            // $("#loading_image").hide();
            // if(logId == null) {
            //     $("#totalPer").append(
            //     "<h5 class='text-center mt-0'>Total " +
            //         item.total_row +
            //     " Cases, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5>"
            //     );
            // }

            // logId = result.log_id;
            // notiId = result.notiId; 


            //         item.id.map((e)=>{
            //             var csrf = document.querySelector('meta[name="csrf-token"]').content;

            //             $.ajax({
            //                 url: "{{ route('admin.case.batchwiseapprove') }}",
            //                 dataType: "json",
            //                 type: "POST",
            //                 data: {
            //                     id: e,
            //                     mediator:item.midater,
            //                     logId: logId,
            //                     _token: csrf,
            //                 },
            //                 success: function (edata) {
            //                     success ++;
            //                     $("#messcc").append(
            //                     "<p style='color: green;' class='text-center'>Case ID : M" +
            //                         e.toString().padStart(6, "0") + " Success.</p>"
            //                     );
            //                     var objDiv = document.getElementById("messcc");
            //                     objDiv.scrollTop = objDiv.scrollHeight;

            //                     if(success === item.id.length) {
            //                         comp = comp + item.id.length;
            //                         prc = Math.round(((comp * 100) / item.total_row));
            //                         $('#tto').html(prc); 
            //                         deferred.resolve(result);
            //                     }
            //                 },
            //                 error: function(err) {
            //                     success ++;
            //                     $("#messcc").append(
            //                     "<p style='color: red;' class='text-center'>Case ID : M" +
            //                         e.toString().padStart(6, "0") + " Fail.</p>"
            //                     );
            //                     if(success === item.id.length) {
            //                         deferred.resolve(err);
            //                     }
            //                 }
            //             });
            //         })
            //     },
            // });
            //         ite.push(result);
            //         comp = comp + 1;
            //         prc = Math.round(((comp * 100) / item.total_row));
            //         $('#tto').html(prc);

            //         if (logId == null) {
            //             $("#loading_image").hide();
            //             $("#totalPer").append(
            //             "<h5 class='text-center mt-0'>Total " +
            //                 item.total_row +
            //             " Case Selected, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5>"
            //             );
            //         }
            //         // if (logId == null) {
            //         //     //$(".loading_image").hide();
            //         //     $("#blkform1_image").html(
            //         //         "<center><h5>Total " +
            //         //         item.total_row +
            //         //         " Case Selected, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5></center>"
            //         //     );
            //         // }

            //         // console.log(result.log_id);
            //         // logId = result.log_id;



            //         if (result.response == "success") {
            //             if (insId == null) {
            //                 insId = result.caseid;
            //                 //console.log('insId '+insId);
            //             } else {
            //                 insId = insId + "," + result.caseid;
            //                 //console.log('insId d '+insId);
            //             }
            //             $("#messcc").append(
            //             "<p style='color: green;' class='text-center'>Case ID : M" +
            //             result.caseid.toString().padStart(6, "0") + " Success.</p>"
            //             );
            //             // $("#mess").append(
            //             //     "<center>Case Id A00" + result.caseid + " Success.</center>"
            //             // );
            //         } else {
            //             if (failId == null) {
            //                 failId = result.caseid;
            //             } else {
            //                 failId = failId + "," + result.caseid;
            //             }
            //             $("#messcc").append(
            //             "<p style='color: red;' class='text-center'>Case ID : M" +
            //             result.caseid.toString().padStart(6, "0") + " Failed.</p>"
            //             );
            //             // $("#mess").append(
            //             //     "<center>Case Id A00" + result.caseid + " Failed.</center>"
            //             // );
            //         }
            //         var objDiv = document.getElementById("messcc");
            //         objDiv.scrollTop = objDiv.scrollHeight;
            //         // var objDiv = document.getElementById("mess");
            //         // objDiv.scrollTop = objDiv.scrollHeight;
            //         deferred.resolve(result);
            //     },
            //     error: function (error) {
            //         if (failId == null) {
            //             failId = item.id;
            //         } else {
            //             failId = failId + "," + item.id;
            //         }
            //         $("#messcc").append(
            //             "<center style='color: red;'>Case ID : M" +
            //             item.cid.toString().padStart(6, "0") + " Failed.</center>"
            //         );
            //         // $("#mess").append(
            //         //     "<center>Case Id A00" + error.caseid + " Failed.</center>"
            //         // );
            //         var objDiv = document.getElementById("messcc");
            //         objDiv.scrollTop = objDiv.scrollHeight;
            //         deferred.reject(error);
            //     },
            //     complete: function () {
            //         swal.close();
            //     },
            // });
            return deferred.promise();
        }


        var looper = $.Deferred().resolve();

        $("#selectalldir").change(function() {
            if (this.checked) {
                $("#bulkAcceptBtn").show();
                $("#bulkRejectBtn").show();
                $(".blkchk").each(function() {
                    if (!this.disabled) {
                        $(this).prop("checked", true);
                    }
                });
            } else {
                $(".blkchk").each(function() {
                    $(this).prop("checked", false);
                });
                $("#bulkAcceptBtn").hide();
                $("#bulkRejectBtn").hide();

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
                $("#bulkAcceptBtn").show();
                $("#bulkRejectBtn").show();
            } else {
                if (case_count == 0) {
                    $("#bulkAcceptBtn").hide();
                    $("#bulkRejectBtn").hide();
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

        $("#selectalldirbulk").change(function() {
            if (this.checked) {
                $("#bulkAcceptBtnBulk").show();
                $("#bulkRejectBtnBulk").show();
                $(".blkchkbulk").each(function() {
                    // $(this).prop("checked", true);
                    if (!this.disabled) {
                        $(this).prop("checked", true);
                    }
                });
            } else {
                $(".blkchkbulk").each(function() {
                    $(this).prop("checked", false);
                });
                $("#bulkAcceptBtnBulk").hide();
                $("#bulkRejectBtnBulk").hide();

            }
        });



        $(document).on("change", ".blkchkbulk", function() {
            var case_count = 0;
            $(".blkchkbulk").each(function() {
                if (this.checked) {
                    case_count++;
                }
            });
            if (this.checked) {
                $("#bulkAcceptBtnBulk").show();
                $("#bulkRejectBtnBulk").show();
            } else {
                if (case_count == 0) {
                    $("#bulkAcceptBtnBulk").hide();
                    $("#bulkRejectBtnBulk").hide();
                }
            }
            if ($('#selectalldirbulk').is(':checked')) {
                $("#bulkAcceptBtnBulk").show();
                $("#bulkRejectBtnBulk").show();
            }
            if (case_count == 0) {
                $("#bulkAcceptBtnBulk").hide();
                $("#bulkRejectBtnBulk").hide();
                $("#selectalldirbulk").prop("checked", false);
            }
        });

        $("#bulkRejectBtn, #bulkRejectBtnBulk").click(function() {
            var ctcnt = 0;

            var blkclon = false;

            var mediatorid = $(this).data("arb");


            $(".blkchk, .blkchkbulk").each(function() {
                if (this.checked) {
                    blkclon = true;
                    ctcnt++;

                }
            });

            if (blkclon == false) {
                swal({
                    title: "Select Cases to accept",
                    text: "",
                    type: "error",
                });
            } else {

                swal({
                    title: "Are you sure?",
                    text: ctcnt + " Cases selected",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        var cids = null;
                        var idarr = [];
                        var ctcnt = 0;
                        $(".blkchk, .blkchkbulk").each(function() {
                            if (this.checked) {
                                ctcnt++;
                                if (cids == null) {
                                    cids = $(this).data("caseid");
                                } else {
                                    cids = cids + "," + $(this).data("caseid");
                                }
                            }
                        });
                        $(".blkchk, .blkchkbulk").each(function() {

                            if (this.checked) {
                                var csrf = document.querySelector('meta[name="csrf-token"]')
                                    .content;

                                idarr.push({
                                    id: $(this).data("caseid"),
                                    token: csrf,
                                    allcids: cids,
                                    total_row: ctcnt,
                                    log_type: "Bulk Reject",
                                });
                            }
                        });
                        var burl = '{{ route('admin.case.reject_status') }}';
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
                                        return ajax_request(item, burl);

                                    });
                                    return looper;
                                })
                            )
                            .then(function() {
                                swal.close();
                                $("#messccclose").append(
                                    '<br><center><a href="{{ route('admin.case.newrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                                );
                                var objDiv = document.getElementById("messcc");
                                objDiv.scrollTop = objDiv.scrollHeight;
                            });
                        // $(".blkchk").each(function() {
                        //     if (this.checked) {
                        //         var csrf = document.querySelector('meta[name="csrf-token"]')
                        //         .content;
                        //         var id = $(this).data("caseid");

                        //         $.ajax({
                        //             url: '{{ route('admin.case.reject_status') }}',
                        //             method: "post",
                        //             data: {
                        //                 id: id,
                        //                 '_token': csrf
                        //             },
                        //             beforeSend: function() {
                        //                 swal({
                        //                     title: 'Loading...',
                        //                     showConfirmButton: false,
                        //                     buttons: false,

                        //                 });
                        //             },
                        //         }).done(function(data) {
                        //             userTableBulk.ajax.reload();
                        //             swal("@lang('case.reject_successfully')", {
                        //                 icon: "success",
                        //             }).then(function() {
                        //                 location.reload();
                        //             });
                        //         });
                        //     }
                        // });

                    } else {
                        swal("@lang('case.cansel_reject_request')");
                    }
                });

            }
        });

        // console.log({{ env('NO_OF_REQUEST_SEND', 10) }});

        $(document).on('click', '#BatchWiseMidaterFormForBulk', function() {
            var batch_id = $("#batchSelectForApprove :selected").val();
            var mediator = $("#BatchWiseMidaterSelect :selected").val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            if (batch_id === "" || mediator === "") {
                swal({
                    title: (batch_id === "") ? "Select Batch" : "Select Mediator",
                    text: "",
                    icon: "error",
                });
            } else {
                swal({
                    title: "@lang('case.are_you_sure')",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then(function(willSuccess) {
                    if (willSuccess) {
                        var idarr = [];
                        // console.log(batch_id);
                        $.ajax({
                            url: "{{ route('admin.case.countbatchwiseapprove') }}",
                            dataType: "json",
                            type: "POST",
                            data: {
                                batch_id: batch_id,
                                _token: csrf,
                            },
                            success: function(result) {
                                $(".ccdd").click();
                                $(".msgDiv").hide();
                                $(".loading_form").show();
                                $("#loading_image").show();
                                $(".close").hide();
                                if (result === 0) {
                                    $("#loading_image").hide();
                                    $("#messcc").append(
                                        "<p style='color: red;' class='text-center'>Not found any data this batch.</p>"
                                    )

                                    var objDiv = document.getElementById("messcc");
                                    objDiv.scrollTop = objDiv.scrollHeight;

                                } else {
                                    countingcases = result;
                                    var actionwork = result /
                                        {{ env('NO_OF_REQUEST_SEND', 10) }};
                                    if (actionwork !== parseInt(actionwork)) {
                                        actionwork = parseInt(actionwork) + 1;
                                    }
                                    for (var i = 0; i < actionwork; i++) {
                                        var csrf = document.querySelector(
                                            'meta[name="csrf-token"]').content;

                                        idarr.push({
                                            batch_id: batch_id,
                                            mediator: mediator,
                                            token: csrf,
                                            total_row: countingcases,
                                            log_type: "Batch Wise Bulk Approve",
                                        });
                                    }
                                }
                                var burl = '{{ route('admin.case.getbatchwiseapprove') }}';
                                swal.close();
                                $.when
                                    .apply(
                                        $,
                                        $.map(idarr, function(item, i) {
                                            looper = looper.then(function() {
                                                return ajax_request_batch_wise(item,
                                                    burl);
                                            });
                                            return looper;
                                        })
                                    )
                                    .then(function() {
                                        swal.close();
                                        $("#messccclose").append(
                                            '<br><center><a href="{{ route('admin.case.newrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                                        );
                                        var objDiv = document.getElementById("messcc");
                                        objDiv.scrollTop = objDiv.scrollHeight;
                                    });
                                // result.map((e)=>{
                                //     // console.log(e.id);
                                //     $.ajax({
                                //         url: "{{ route('admin.case.batchwiseapprove') }}",
                                //         dataType: "json",
                                //         type: "POST",
                                //         data: {
                                //             id: e.id,
                                //             mediator:mediator,
                                //             _token: csrf,
                                //         },
                                //         success: function (result) { 
                                //             console.log(result);
                                //         }

                                //     });
                                // })
                                // if(result.status == 'success') {
                                //     swal({
                                //         title: result.msg,
                                //         text: "",
                                //         icon: "error",
                                //     });
                                // }
                                // swal({
                                //     title: result.msg,
                                //     text: "",
                                //     icon: "success",
                                // }).then(function() {
                                //     location.reload();
                                // });
                            }

                        });
                    }
                    // console.log(batch_id);

                });
            }
        })


        // $('#bulkWiseApproveBtn').on('click', function() {
        //     // console.log("hello");
        //     var batch_id = $("#batchSelectForApprove :selected").val();
        //     var csrf = document.querySelector('meta[name="csrf-token"]').content;

        //     if(batch_id === "") {
        //         swal({
        //             title: "Select Batch",
        //             text: "",
        //             icon: "error",
        //         });
        //     } else {
        //         swal({
        //             title: "@lang('case.are_you_sure')",
        //             text:  "",
        //             icon: "warning",
        //             buttons: true,
        //             dangerMode: true,
        //         }).then(function(willSuccess) {
        //             if(willSuccess) {
        //                 // console.log(batch_id);
        //                 $.ajax({
        //                     url: "{{ route('admin.case.batchwiseapprove') }}",
        //                     dataType: "json",
        //                     type: "POST",
        //                     data: {
        //                         id: batch_id,
        //                         _token: csrf,
        //                     },
        //                     success: function (result) { 
        //                         console.log(result);
        //                     }

        //                 });
        //             }
        //             // console.log(batch_id);

        //         });
        //     }
        // });


        // $(document).on('submit', "#MidaterFormForBulk", function() {
        //     console.log("j")
        // });


        // $(document).on('submit', "#MidaterFormForBulk", function() {
        //     // var id = $(this).find("input[name='id']").val();
        //     var blkclon = false;
        //     var withdrawcount = [];
        //     var count = 0;

        //     var midater = $(this).find("select[name='midater']").val();
        //     var csrf = document.querySelector('meta[name="csrf-token"]').content;
        //     $(".blkchk").each(function() {
        //         if (this.checked) {
        //             blkclon = true;
        //             count++;

        //         }
        //         withdrawcount.push(count);

        //     });
        //     var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

        //     if (blkclon == false) {
        //         swal({
        //             title: "Select arbitration to accept",
        //             text: "",
        //             type: "error",
        //         });
        //     } else {
        //         swal({
        //             title: "@lang('case.are_you_sure')",
        //             text: withdrawcountTotal.toString() + " Cases selected",
        //             icon: "warning",
        //             buttons: true,
        //             dangerMode: true,
        //         }).then((willDelete) => {
        //             if (willDelete) {
        //                 // var ids = null;

        //                 var cids = null;
        //                 var idarr = [];
        //                 var ctcnt = 0;
        //                 var cidsarray = [];

        //                 $(".blkchk").each(function() {
        //                     if (this.checked) {
        //                         cidsarray.push($(this).data("caseid"));
        //                         ctcnt++;
        //                         if (cids == null) {
        //                             cids = $(this).data("caseid");
        //                         } else {
        //                             cids = cids + "," + $(this).data("caseid");
        //                         }
        //                     }
        //                 });

        //                 // var work = 
        //                 // const items = cidsarray.slice(10, 20)
        //                 // console.log(cidsarray);
        //                 var actionwork = (cidsarray.length)/{{ env('NO_OF_REQUEST_SEND', 10) }};
        //                 if(actionwork!==parseInt(actionwork)) {
        //                     actionwork = parseInt(actionwork) + 1;
        //                 }
        //                 // console.log(actionwork);
        //                 var first = 0;
        //                 var last = parseInt({{ env('NO_OF_REQUEST_SEND', 10) }});
        //                 for(var i=0; i<actionwork; i++) {
        //                     console.log("First", first);
        //                     console.log("last", last);
        //                     idarr.push({
        //                         id: cidsarray.slice(first, last),
        //                         token: csrf,
        //                         allcids: cids,
        //                         midater: midater,
        //                         total_row: ctcnt,
        //                         log_type: "Bulk Approve",
        //                     });
        //                     // console.log(cidsarray.slice(first, last));
        //                     first += {{ env('NO_OF_REQUEST_SEND', 10) }};
        //                     last += {{ env('NO_OF_REQUEST_SEND', 10) }};
        //                 }



        //                 console.log(idarr);
        //                 // $(".blkchk").each(function() {
        //                 //     if (this.checked) {
        //                 //         var caseid = $(this).data("caseid");

        //                 //         idarr.push({
        //                 //             id: caseid,
        //                 //             token: csrf,
        //                 //             allcids: cids,
        //                 //             midater: midater,
        //                 //             total_row: ctcnt,
        //                 //             log_type: "Bulk Approve",
        //                 //         });
        //                 //     }
        //                 // });

        //                 var add_mediater = '{{ route('admin.case.midater_add') }}';
        //                 var confirm = '{{ route('admin.case.confirm_status') }}';
        //                 swal.close();
        //                 $(".ccdd").click();
        //                 $(".msgDiv").hide();
        //                 $(".loading_form").show();
        //                 $("#loading_image").show();
        //                 $(".close").hide();
        //                 $.when
        //                     .apply(
        //                         $,
        //                         $.map(idarr, function(item, i) {
        //                             looper = looper.then(function() {

        //                                 return ajax_request_approve(item, add_mediater, confirm);

        //                             });
        //                             return looper;

        //                         })
        //                     )
        //                     .then(function() {
        //                         swal.close();
        //                         // $("#myModalcc").hide();
        //                         $("#messccclose").append(
        //                             '<br><center><a href="{{ route('admin.case.newrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
        //                         );
        //                         var objDiv = document.getElementById("messcc");
        //                         objDiv.scrollTop = objDiv.scrollHeight;
        //                     });


        //                 // $(".blkchk").each(function() {
        //                 //     if (this.checked) {
        //                 //         var id = $(this).data("caseid");
        //                 //         // console.log(id);
        //                 //         $.ajax({
        //                 //             url: '{{ route('admin.case.midater_add') }}',
        //                 //             method: "post",
        //                 //             data: {
        //                 //                 id: id,
        //                 //                 midater: midater,
        //                 //                 '_token': csrf
        //                 //             },
        //                 //         }).done(function(data) {
        //                 //             $.ajax({
        //                 //                 url: '{{ route('admin.case.confirm_status') }}',
        //                 //                 method: "post",
        //                 //                 data: {
        //                 //                     id: id,
        //                 //                     '_token': csrf
        //                 //                 },
        //                 //                 beforeSend: function() {
        //                 //                     swal({
        //                 //                         title: 'Loading...',
        //                 //                         showConfirmButton: false,
        //                 //                         buttons: false,

        //                 //                     });
        //                 //                 },
        //                 //             }).done(function(data) {
        //                 //                 userTableBulk.ajax.reload();
        //                 //                 swal("@lang('case.confirm_successfully')", {
        //                 //                     icon: "success",
        //                 //                 }).then(function() {
        //                 //                     location.reload();
        //                 //                 });
        //                 //                 $('#midaterAddForBulk').modal("hide");

        //                 //             });
        //                 //             //userTableBulk.ajax.reload();
        //                 //         });
        //                 //     }
        //                 // });

        //             } else {
        //                 swal("@lang('case.cansel_confirm_request')");
        //             }
        //         });
        //     }



        //     return false;
        // });

        $(document).on('submit', "#MidaterFormForBulk", function() {
            // var id = $(this).find("input[name='id']").val();
            var blkclon = false;
            var withdrawcount = [];
            var count = 0;

            var midater = $(this).find("select[name='midater']").val();
            if ($(this).find('.bulkTabDis')) {
                var discussion = $(this).find("input[name='contact_for_discussion']").val();
            }
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            $(".blkchk, .blkchkbulk").each(function() {
                if (this.checked) {
                    blkclon = true;
                    count++;

                }
                withdrawcount.push(count);

            });
            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

            // console.log(midater);
            if (midater != "") {
                if (blkclon == false) {
                    swal({
                        title: "Select Cases to accept",
                        text: "",
                        type: "error",
                    });
                } else {
                    swal({
                        title: "@lang('case.are_you_sure')",
                        text: withdrawcountTotal.toString() + " Cases selected",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            // var ids = null;

                            var cids = null;
                            var idarr = [];
                            var ctcnt = 0;
                            var cidsarray = [];

                            $(".blkchk, .blkchkbulk").each(function() {
                                if (this.checked) {
                                    cidsarray.push($(this).data("caseid"));
                                    ctcnt++;
                                    if (cids == null) {
                                        cids = $(this).data("caseid");
                                    } else {
                                        cids = cids + "," + $(this).data("caseid");
                                    }
                                }
                            });

                            // var work = 
                            // const items = cidsarray.slice(10, 20)
                            // console.log(cidsarray);
                            var actionwork = (cidsarray.length) / {{ env('NO_OF_REQUEST_SEND', 10) }};
                            if (actionwork !== parseInt(actionwork)) {
                                actionwork = parseInt(actionwork) + 1;
                            }
                            // console.log(actionwork);
                            var first = 0;
                            var last = parseInt({{ env('NO_OF_REQUEST_SEND', 10) }});
                            for (var i = 0; i < actionwork; i++) {
                                if ($(this).find('.bulkTabDis')) {
                                    idarr.push({
                                        id: cidsarray.slice(first, last),
                                        token: csrf,
                                        allcids: cids,
                                        midater: midater,
                                        //discussion: discussion, 
                                        total_row: ctcnt,
                                        log_type: "Bulk Approve",
                                    });
                                } else {
                                    idarr.push({
                                        id: cidsarray.slice(first, last),
                                        token: csrf,
                                        allcids: cids,
                                        midater: midater,
                                        discussion: discussion,
                                        total_row: ctcnt,
                                        log_type: "Bulk Approve",
                                    });
                                }
                                first += {{ env('NO_OF_REQUEST_SEND', 10) }};
                                last += {{ env('NO_OF_REQUEST_SEND', 10) }};
                            }
                            // console.log(idarr);

                            // var add_mediater = '{{ route('admin.case.midater_add') }}';
                            var confirm = '{{ route('admin.case.confirm_status_with_midater_add') }}';
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

                                            return ajax_request_approve_with_midater_add(item,
                                                confirm);

                                        });
                                        return looper;

                                    })
                                )
                                .then(function() {
                                    swal.close();
                                    // $("#myModalcc").hide();
                                    $("#messccclose").append(
                                        '<br><center><a href="{{ route('admin.case.newrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                                    );
                                    var objDiv = document.getElementById("messcc");
                                    objDiv.scrollTop = objDiv.scrollHeight;
                                });

                        } else {
                            swal("@lang('case.cansel_confirm_request')");
                        }
                    });
                }

            } else {
                swal({
                    title: "Select Mediator",
                    text: "",
                    icon: "error",
                });
            }

            return false;
        });


        $(document).on('click', ".reject", function() {
            var id = $(this).val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            swal({
                title: "Are you sure?",
                text: "Reject this request!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route('admin.case.reject_status') }}',
                        method: "post",
                        data: {
                            id: id,
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
                        if (typeof userTable !== "undefined") {
                            userTable.ajax.reload(null, false);
                        } else {
                            userTableBulk.ajax.reload(null, false);
                        }
                        swal("@lang('case.reject_successfully')", {
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    });
                } else {
                    swal("@lang('case.cansel_reject_request')");
                }
            });
        });


        $(document).on('submit', '#bulkUploadForm', function(e) {
            e.preventDefault();

            swal({
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.confirm_this_request')",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route('admin.bulkUpload') }}',
                        method: "post",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,

                            });
                        },
                        success: function(data) {
                            var result = $.parseJSON(data);

                            if (result.response == "success") {
                                swal("Successfully upload file", {
                                    icon: "success",
                                });
                                if (typeof userTable !== "undefined") {
                                    userTable.ajax.reload(null, false);
                                } else {
                                    userTableBulk.ajax.reload(null, false);
                                }
                                $('#bulkUploadForm')[0].reset();
                                $(".dropify-clear").trigger("click");
                                $("#myModalbupldAdmin").modal("hide");
                            } else {
                                swal(result.msg, {
                                    icon: "error",
                                });
                            }
                        }
                    })
                }
            });
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

        // $(document).on('click', '#fordocumentupload', function() {
        //     // console.log("hello");
        //     let userid = $(this).data('id');
        //     $("#UploadDocumentForm :input[name='userid']").val(userid);
        // });

        $('#documentUpload').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('id') // Extract info from data-* attributes
            var modal = $(this);
            modal.find('#recipientCaseid').val(recipient);

        });

        $('#UploadDocumentForm').on('submit', function(e) {
            e.preventDefault();

            swal({
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.confirm_this_request')",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route('admin.documentUpload') }}',
                        method: "post",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            swal({
                                title: 'Loading...',
                                showConfirmButton: false,
                                buttons: false,

                            });
                        },
                        success: function(data) {
                            // console.log(data);
                            var result = $.parseJSON(data);

                            if (result.response == "success") {
                                swal("Successfully upload file", {
                                    icon: "success",
                                });
                                if (typeof userTable !== "undefined") {
                                    userTable.ajax.reload(null, false);
                                } else {
                                    userTableBulk.ajax.reload(null, false);
                                }
                                $('#UploadDocumentForm')[0].reset();
                                $(".dropify-clear").trigger("click");
                                $("#documentUpload").modal("hide");
                            } else {
                                swal(result.msg, {
                                    icon: "error",
                                });
                            }
                        }
                    });
                    // if (data.response == "success") {

                    //     swal("@lang('case.mediator_assigned_successfully')", {
                    //         icon: "success",
                    //     });
                    //     $.ajax({
                    //         url: '{{ route('admin.case.confirm_status') }}',
                    //         method: "post",
                    //         data: {
                    //             id: id,
                    //             '_token': csrf
                    //         },
                    //         beforeSend: function() {
                    //             swal({
                    //                 title: 'Loading...',
                    //                 showConfirmButton: false,
                    //                 buttons: false,

                    //             });
                    //         },
                    //     }).done(function(data) {
                    //         if (typeof userTable !== "undefined") {
                    //             userTable.ajax.reload(null, false);
                    //         } else {
                    //             userTableBulk.ajax.reload(null, false);
                    //         }
                    //         swal("@lang('case.confirm_successfully')", {
                    //             icon: "success",
                    //         }).then(function() {
                    //             location.reload();
                    //         });
                    //     });
                    //     //userTableBulk.ajax.reload();
                    //     $('#midaterAdd').modal("hide");
                    // } else {
                    //     swal("Please Select Mediator", {
                    //         icon: "error",
                    //     });
                    // }

                } else {
                    swal("@lang('case.cansel_confirm_request')");
                }
            });
        })

        $(document).on('submit', "#MidaterForm", function() {
            var id = $(this).find("input[name='id']").val();
            var midater = $(this).find("select[name='midater']").val();
            var discussion_text = $(this).find("input[name='contact_for_discussion']").val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var input_type = "input";
            var field_type = "text";
            // setTimeout(function () {  

            //$('#midaterAdd').modal('hide');
            swal({
                title: "@lang('case.are_you_sure')",
                text: "@lang('case.confirm_this_request')",
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
                    }).done(function(data) {
                        if (data.response == "success") {
                            swal("@lang('case.mediator_assigned_successfully')", {
                                icon: "success",
                            });

                            $.ajax({
                                url: '{{ route('admin.case.confirm_status') }}',
                                method: "post",
                                data: {
                                    id: id,
                                    discussion: discussion_text,
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
                                if (typeof userTable !== "undefined") {
                                    userTable.ajax.reload(null, false);
                                } else {
                                    userTableBulk.ajax.reload(null, false);
                                }
                                swal("@lang('case.confirm_successfully')", {
                                    icon: "success",
                                }).then(function() {
                                    location.reload();
                                });
                            });
                            //userTableBulk.ajax.reload();
                            $('#midaterAdd').modal("hide");
                        } else {
                            swal(data.msg, {
                                icon: "error",
                            });
                        }
                    });


                } else {
                    swal("@lang('case.cansel_confirm_request')");
                    // /$('#midaterAdd').modal('show');
                }
            });
            // }, 2500);
            return false;
        });
        $('#midaterAdd').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('id') // Extract info from data-* attributes
            // Added for 'Contact for discussion' : START //
            var bulk_flag = button.data('bulk') // Extract info from data-* attributes
            // Added for 'Contact for discussion' : END //

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-body input[name="id"]').val(recipient)
            // Added for 'Contact for discussion' : START //
            if (bulk_flag == 0) {
                modal.find('.modal-body .discussion-section').show();
            } else {
                modal.find('.modal-body .discussion-section').hide();
            }
            //modal.find('.modal-body input[name="contact_for_discussion"]').val(discussion_text)
            // Added for 'Contact for discussion' : END //
        });


        $('#midaterAddForBulk').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal

            // Added for 'Contact for discussion' : START //
            var bulk_flag = button.data('bulk') // Extract info from data-* attributes
            // Added for 'Contact for discussion' : END //

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)

            //alert(bulk_flag);
            // Added for 'Contact for discussion' : START //
            if (bulk_flag == 'ind') {
                modal.find('.modal-body .discussion-section').show();
                modal.find('form').removeClass('bulkTabDis');
            } else {
                modal.find('.modal-body .discussion-section').hide();
                modal.find('form').addClass('bulkTabDis');
            }
            //modal.find('.modal-body input[name="contact_for_discussion"]').val(discussion_text)
            // Added for 'Contact for discussion' : END //
        });



        <?php if(session()->has('success')) {?>
        swal({
            title: '{{ session()->get('success') }}',
            // text: "Withdraw case!",
            icon: "success",
            buttons: true,
        }).then(function() {
            window.location = "{{ route('admin.case.newrequest') }}"
        });

        <?php } if(session()->has('error')) {?>
        swal({
            title: "Error",
            text: '{{ session()->get('error') }}',
            icon: "error",
            buttons: true,
            dangerMode: true,
        }).then(function() {
            window.location = "{{ route('admin.case.newrequest') }}"
        });
        <?php } ?>




        // on change claimant event //
        $('#claimant').change(function(){

            var parent_id = $(this).val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;

            $.ajax({
                url: '{{ route('admin.getSubUserList') }}',
                method: "POST",
                data: {
                    parent_id: parent_id,
                    _token: csrf
                },
                success: function(resp) {
                    $('#subuser').empty();

                    if(resp.user_data != ""){
                        var option_html = "<option value='0'>Select Sub User</option>";
                        $(resp.user_data).each(function( index, element ) { 
                            option_html += "<option value='"+element.id+"' name='sub_user_id'>"+element.first_name +" "+ element.last_name+"</option>" ; 
                        });
                    } else {
                        var option_html = "<option value='0'>No Sub Users</option>";  
                    }

                    

                    $('#subuser').html(option_html);
                },

                error: function(err) {
                    console.log(err);
                },
            });
        });
        // on change claimant event //
    </script>
@endsection
