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
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-sm" data-target="#myModalbupldAdmin" data-toggle="modal"> Bulk
                            Upload</button>
                        <br>
                        <br>
                    </div>
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
                </div>
                <div id="myModalbupldAdmin" class="mdladcm modal fade " role="dialog" data-keyboard="false"
                    data-backdrop="static">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                {{-- {{dd($allUsers)}} --}}
                                <div class="blkfrmdiv">
                                    <h3>Upload .csv file</h3>
                                    <form enctype="multipart/form-data" method="post"
                                        action="{{ route('admin.bulkUpload') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="token" id="token_input">
                                        <div class="form-group">
                                            <select class="form-control" name="claimant" required>
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

                                        <div class="form-group">
                                            <input type="file" name="csv" id="fileInput" onchange=""
                                                class="col-md-12 dropify" data-allowed-file-extensions="csv" required=""
                                                data-max-file-size="20M" />
                                        </div>
                                        <div class="form-group">
                                            {{-- <label for="batch" class="col-md-5">Batch Name: </label> --}}
                                            <input type="text" name="batch" id="batch" placeholder="Batch Name"
                                                class="col-md-12 form-control" />
                                        </div>
                                        <input type="Submit" value="Submit" class="btn btn-primary blkupdbtnsb">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"
                                            aria-label="Close">
                                            <span>@lang('case.btn_close')</span>
                                        </button>
                                    </form>

                                </div>
                                {{-- <div class="loading_form" style="display: none;">
                        <center>

                            </center>
                        <center><p>Please Wait. Do Not Close Until Close Button Appear.</p></center>

                        <div style="height: 200px;
        overflow-y: scroll;" id="mess">

                        </div>
                        <!-- <a>Close</a> -->
                    </div> --}}

                            </div>
                        </div>

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
                                        class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                            <th>@lang('case.party_details')</th>
                            <th>@lang('Supporting Document')</th>
                            <th>@lang('case.action') <a href="#" data-toggle="tooltip" title=""
                                    data-original-title="Click 'Confirm' to register the Mediation (after assigning an mediator). Click 'Reject' to decline the Mediation."><i
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
                        <button class="btn btn-success btn-sm blkbtn" data-toggle="modal" data-target="#midaterAddForBulk"
                            id="bulkAcceptBtn" style="margin-top:10px; display:none;"
                            data-arb="<?= Auth::user()->id ?>">Bulk Approve</button>
                        <button class="btn btn-danger btn-sm blkbtn" id="bulkRejectBtn"
                            style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk Reject</button>
                    </div>
                    

                </div>
                <br><br>
                <div class="row">
                    <div class="col-md-4">
                        <select name="batch" id="batchSelectForApprove" class="form-control">
                            <option value="" selected>Select Batch...</option>
                            @foreach ($batchName as $value)
                                <option value={{ $value->id }}>{{ $value->batch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#batchWiseMidaterAddForBulk" id="batchWiseApproveBtn"
                         data-arb="<?= Auth::user()->id ?>">Batch Wise Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} -
                                            {{ $user->organization }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('case.btn_close')</button>
                        <button type="submit" class="btn btn-primary">@lang('case.btn_accept')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="midaterAddForBulk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('case.btn_close')</button>
                        <button type="submit" class="btn btn-primary">@lang('case.btn_accept')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="batchWiseMidaterAddForBulk" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">@lang('case.btn_close')</button>
                        <button type="button" id="BatchWiseMidaterFormForBulk" class="btn btn-primary">@lang('case.btn_accept')</button>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>

    <button style="display:none;" type="button" class="btn btn-info btn-lg cc1" data-toggle="modal" data-target="#msg1">MSG</button>
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
    overflow-y: scroll;" id="mess">
                        
                    </div>
                    <!-- <a>Close</a> -->
                </div>
                   
                </div>
            </div>

        </div>
    </div>

<div class="row">
    <div class="col-md-12">
            <button style="display:none;" class="btn btn-sucess ccdd" data-target="#myModalcc" data-toggle="modal"></button>


<div id="myModalcc" class="mdladcm modal fade " role="dialog" data-keyboard="false" data-backdrop="static">
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
                        <img src="{{url('assets/')}}/images/loading_form.gif" >
                    </span>
                    {{-- </center> --}}
                
                    
                    <!-- <div><a href="ongoing" class="btn btn-danger btn-lg directionCloseSwal" style="display: none;">Close</a></div> -->
                </div>
                <div id="totalPer"></div>
                <div style='margin: auto; max-height: 100px; position:sticky; overflow-y:scroll;' id="messcc">
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

@endsection


@section('footer')
    <!-- Datatable plugin js -->
    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>

        $(document).ready(function() {
            $('.dropify').dropify();
        });

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
            "columns": [

                {
                    "data": "key"
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
                    render: function(data, type, row) {
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
                        return button;
                    }
                },
                {
                    "data": "party",
                    render: function(data, type, row) {
                        var d = "";
                        for (i in data) {
                            // console.log(data[i].documentPath);

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
                                    d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                                }
                            }
                        }

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
                            d = d + `<p  class="btn btn-success btn-sm">` + data.documentPath + `</p>`;
                        } else {
                            d = d + `<form action="{{ url('admin/uploaddocument/') }}/` + data.id + `"  method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input  class="form-control dropify" type="file" id="document" name="document" data-allowed-file-extensions="pdf zip rar"  data-max-file-size="20M"></input>
                                    <p>*Only Pdf zip and rar file allowed</p>
                                    <input type="submit" class="btn btn-primary btn-sm" id="upload" value="Upload">
                                    </form>`;
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
                            `" data-toggle="modal" data-target="#midaterAdd"  class="btn btn-info ` + d +
                            `">Approve</button>`;
                        button = button + ` <button value="` + data + `" class="btn btn-danger reject ` +
                            d + `">@lang('case.btn_reject')</button>`;
                        return button;
                    }
                },
            ],
        });

var insertRow = false;
var logId = null;
var insId = null;
var failId = null;

var ite = [];
var comp = 0;
var prc = 0;

var ajax_request = function (item, url) {
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
            midater : item.midater,
            log_id: logId,
            insertRow: insId,
            faildRow: failId,
            log_type: item.log_type,
        },
        success: function (result) {

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
        error: function (error) {
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
        complete: function () {
            swal.close();
        },
    });
    return deferred.promise();
};

var ajax_request_approve = function (item, mediator_url, confirm_url) {
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
            midater : item.midater,
            // insertRow: insId,
            // faildRow: failId,
            // log_type: item.log_type,
        },
        success: function (result) {
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
                error: function (error) {
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
                complete: function () {
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

var ajax_request_batch_wise = function (item, confirm_url) {
    var deferred = $.Deferred();
    $.ajax({
        url: confirm_url,
        dataType: "json",
        type: "POST",
        data: {
            batch_id: item.batch_id,
            _token: item.token,
            logId: logId,
            // allcids: item.allcids,
            total_row: item.total_row,
            // midater : item.midater,
            // insertRow: insId,
            // faildRow: failId,
            log_type: item.log_type,
        },
        success: function (result) {
            // console.log(result);
            logId = result.log_id; 
            var success = 0;
            comp = comp + result.data.length;
            prc = Math.round(((comp * 100) / countingcases));
            $('#tto').html(prc); 

            result.data.map((e)=>{
                var csrf = document.querySelector('meta[name="csrf-token"]').content;

                $.ajax({
                    url: "{{ route('admin.case.batchwiseapprove') }}",
                    dataType: "json",
                    type: "POST",
                    data: {
                        id: e.id,
                        mediator:item.mediator,
                        logId: logId,
                        _token: csrf,
                    },
                    success: function (edata) {
                        
                        success ++;
                        $("#messcc").append(
                        "<p style='color: green;' class='text-center'>Case ID : M" +
                            e.id.toString().padStart(6, "0") + " Success.</p>"
                        );
                        var objDiv = document.getElementById("messcc");
                        objDiv.scrollTop = objDiv.scrollHeight;
                        if(success === result.data.length) {
                            $("#loading_image").hide();

                            $("#totalPer").append(
                            "<h5 class='text-center mt-0'>Total " +
                                countingcases +
                            " Case Selected, <span id='tto'><b>" + prc + "</span> %</b> Completed.</h5>"
                            );
                            deferred.resolve(result);
                        }
                    },
                    error: function(err) {
                        $("#messcc").append(
                        "<p style='color: red;' class='text-center'>Case ID : M" +
                            e.id.toString().padStart(6, "0") + " Fail.</p>"
                        );
                        deferred.resolve(err);
                    }
                });
            })
            
            // deferred.resolve(result);
        },
    });
    return deferred.promise();
}


var looper = $.Deferred().resolve();

        $("#selectalldir").change(function() {
            if (this.checked) {
                $("#bulkAcceptBtn").show();
                $("#bulkRejectBtn").show();
                $(".blkchk").each(function() {
                    $(this).prop("checked", true);
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

        $("#bulkRejectBtn").click(function() {
            var ctcnt = 0;

            var blkclon = false;

            var mediatorid = $(this).data("arb");


            $(".blkchk").each(function() {
                if (this.checked) {
                    blkclon = true;
                    ctcnt++;

                }
            });

            if (blkclon == false) {
                swal({
                    title: "Select arbitration to accept",
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
                        $(".blkchk").each(function () {
                            if (this.checked) {
                            ctcnt++;
                                if (cids == null) {
                                    cids = $(this).data("caseid");
                                } else {
                                    cids = cids + "," + $(this).data("caseid");
                                }
                            }
                        });
                        $(".blkchk").each(function () {
                    
                            if (this.checked) {
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;
        
                            idarr.push({
                                id: $(this).data("caseid"),
                                token: csrf,
                                allcids: cids,
                                total_row: ctcnt,
                                log_type: "Bulk Reject",
                            });
                            }
                        });
                        var burl = '{{ route("admin.case.reject_status") }}';
                        swal.close();
                        $(".ccdd").click();
                        $(".msgDiv").hide();
                        $(".loading_form").show();
                        $("#loading_image").show();
                        $(".close").hide();
                        $.when
                        .apply(
                        $,
                        $.map(idarr, function (item, i) {
                            looper = looper.then(function () {
                                return ajax_request(item, burl);
            
                            });
                            return looper;
                        })
                        )
                        .then(function () {
                            swal.close();
                            $("#messccclose").append(
                                '<br><center><a href="{{route("admin.case.newrequest")}}" class="btn btn-danger btn-lg">Close</a></center>'
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
                        //             userTable.ajax.reload();
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

        $(document).on('click', '#BatchWiseMidaterFormForBulk', function(){
            var batch_id = $("#batchSelectForApprove :selected").val();
            var mediator = $("#BatchWiseMidaterSelect :selected").val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            if(batch_id === "" || mediator === "") {
                swal({
                    title: (batch_id === "") ? "Select Batch" : "Select Mediator",
                    text: "",
                    icon: "error",
                });
            } else {
                swal({
                    title: "@lang('case.are_you_sure')",
                    text:  "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then(function(willSuccess) {
                    if(willSuccess) {
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
                            success: function (result) {
                                $(".ccdd").click();
                                $(".msgDiv").hide();
                                $(".loading_form").show();
                                $("#loading_image").show();
                                $(".close").hide();
                                if(result === 0) {
                                    $("#loading_image").hide();
                                    $("#messcc").append("<p style='color: red;' class='text-center'>Not found any data this batch.</p>")
                                    
                                    var objDiv = document.getElementById("messcc");
                                    objDiv.scrollTop = objDiv.scrollHeight;

                                } else {
                                    countingcases = result;
                                    var actionwork = result/10;
                                    if(actionwork!==parseInt(actionwork)) {
                                        actionwork = parseInt(actionwork) + 1;
                                    } 
                                    console.log(actionwork);
                                    for(var i=0; i<actionwork; i++) {
                                        var csrf = document.querySelector('meta[name="csrf-token"]').content;
            
                                        idarr.push({
                                            batch_id: batch_id,
                                            mediator: mediator,
                                            token: csrf,
                                            total_row: countingcases,
                                            log_type: "Batch Wise Bulk Approve",
                                        });
                                    }
                                }
                                var burl = '{{ route("admin.case.getbatchwiseapprove") }}';
                                swal.close();
                                $.when
                                .apply(
                                $,
                                $.map(idarr, function (item, i) {
                                    looper = looper.then(function () {
                                        return ajax_request_batch_wise(item, burl);
                                    });
                                    return looper;
                                })
                                )
                                .then(function () {
                                    swal.close();
                                    $("#messccclose").append(
                                        '<br><center><a href="{{route("admin.case.newrequest")}}" class="btn btn-danger btn-lg">Close</a></center>'
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

        $(document).on('submit', "#MidaterFormForBulk", function() {
            // var id = $(this).find("input[name='id']").val();
            var blkclon = false;
            var withdrawcount = [];
            var count = 0;

            var midater = $(this).find("select[name='midater']").val();

            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            $(".blkchk").each(function() {
                if (this.checked) {
                    blkclon = true;
                    count++;

                }
                withdrawcount.push(count);

            });
            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

            if (blkclon == false) {
                swal({
                    title: "Select arbitration to accept",
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

                        $(".blkchk").each(function() {
                            if (this.checked) {
                                ctcnt++;
                                if (cids == null) {
                                    cids = $(this).data("caseid");
                                } else {
                                    cids = cids + "," + $(this).data("caseid");
                                }
                            }
                        });

                        $(".blkchk").each(function() {
                            if (this.checked) {
                                var caseid = $(this).data("caseid");

                                idarr.push({
                                    id: caseid,
                                    token: csrf,
                                    allcids: cids,
                                    midater: midater,
                                    total_row: ctcnt,
                                    log_type: "Bulk Approve",
                                });
                            }
                        });

                        var add_mediater = '{{ route('admin.case.midater_add') }}';
                        var confirm = '{{ route('admin.case.confirm_status') }}';
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

                                        return ajax_request_approve(item, add_mediater, confirm);

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


                        // $(".blkchk").each(function() {
                        //     if (this.checked) {
                        //         var id = $(this).data("caseid");
                        //         // console.log(id);
                        //         $.ajax({
                        //             url: '{{ route('admin.case.midater_add') }}',
                        //             method: "post",
                        //             data: {
                        //                 id: id,
                        //                 midater: midater,
                        //                 '_token': csrf
                        //             },
                        //         }).done(function(data) {
                        //             $.ajax({
                        //                 url: '{{ route('admin.case.confirm_status') }}',
                        //                 method: "post",
                        //                 data: {
                        //                     id: id,
                        //                     '_token': csrf
                        //                 },
                        //                 beforeSend: function() {
                        //                     swal({
                        //                         title: 'Loading...',
                        //                         showConfirmButton: false,
                        //                         buttons: false,

                        //                     });
                        //                 },
                        //             }).done(function(data) {
                        //                 userTable.ajax.reload();
                        //                 swal("@lang('case.confirm_successfully')", {
                        //                     icon: "success",
                        //                 }).then(function() {
                        //                     location.reload();
                        //                 });
                        //                 $('#midaterAddForBulk').modal("hide");

                        //             });
                        //             //userTable.ajax.reload();
                        //         });
                        //     }
                        // });

                    } else {
                        swal("@lang('case.cansel_confirm_request')");
                    }
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
                        userTable.ajax.reload();
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
        $(document).on('submit', "#MidaterForm", function() {
            var id = $(this).find("input[name='id']").val();
            var midater = $(this).find("select[name='midater']").val();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
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
                        swal("@lang('case.mediator_assigned_successfully')", {
                            icon: "success",
                        });
                        $.ajax({
                            url: '{{ route('admin.case.confirm_status') }}',
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
                            userTable.ajax.reload()
                            swal("@lang('case.confirm_successfully')", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                        });
                        //userTable.ajax.reload();
                        $('#midaterAdd').modal("hide");
                    });


                } else {
                    swal("@lang('case.cansel_confirm_request')");
                }
            });
            return false;
        });
        $('#midaterAdd').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('id') // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-body input[name="id"]').val(recipient)
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
    </script>
@endsection
