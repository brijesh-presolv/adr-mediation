@extends('admin.layouts.app')
@section('title', 'Ongoing Request')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.ongoing_request')</a></li>
    <!-- end page title -->
@endsection
@section('page_title', 'Ongoing Request')

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

    #coolModal p {
        margin-bottom: 0px;
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
                                        data-max-file-size="500M" />
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
                                        data-max-file-size="500M" /> 
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
                    <table id="usersbulk" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <!-- <th>Select</th> -->
                                <th>@lang('case.case_id') </th>
                                <th>@lang('case.ref_id')</th>
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('case.mediator') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click on 'Mediator Name' to withdraw current Mediator and/or appoint new Mediator."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <!-- <th>@lang('case.comment') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Private comments are for internal reference only. Shared comments are visible to the appointed Mediator. Comments are not visible to the parties.
                                                                                                                                                                                                                                         "><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a>
                                </th> -->
                                <th>@lang('case.session') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Schedule meeting date and time. Parties will be notified via email."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.action') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to upload any document/s in relation to the case."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
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
                        <div class="col-md-10">
                            <button class="blkbtn btn btn-teal waves-light waves-effect btn-sm" data-toggle="modal"
                                data-target="#withdrawModalForBulk" id="bulkCloseBtnBulkcases"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk
                                Close</button>
                            <button class="blkbtn btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#uploadSupportingDocsModalForBulk" id="bulkUploadBulkcases"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload
                                Supporting
                                Documents</button>
                            <button class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal"
                                data-target="#addSessionModelForBulk" id="bulkSessionBulkcases"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>"><span
                                    class="mdi mdi-pencil-plus"></span></button>
                            <button class="blkbtn btn btn-primary btn-sm" id="bulkdownloadBulkcases" data-toggle="modal"
                                data-target="#bulkdownloadBulkcasesModal"
                                style="margin-top:10px; display:none;">Bulk Download</button>
                            <button class="blkbtn btn btn-primary btn-sm" id="downloadExcelBulkcases"
                                style="margin-top:10px; display:none;">Download Invitation Delivery Sheet</button>

                        </div>
                    </div>


                    <!----- Added for random cases : START ---------------->
                    <div>
                        
                            <div class="row">
                                <div class="col-md-2">
                                    <input type="radio" id="randomCase">&nbsp;&nbsp;For Random Cases
                                </div>
                            </div>

                            <div class="row" id="randomCaseSec" style="display:none;">
                                <div class="col-md-4">
                                    <!-- <div class="form-group"> -->
                                        <select class="blkdirtorandom" name="rCases" id="rCases" multiple style="width:50%">
                                        </select>
                                    <!-- </div> -->
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal"
                                    data-target="#addSessionModelForBulkRandom" id="bulkRandomCases" data-arb="<?= Auth::user()->id ?>"><span
                                        class="mdi mdi-pencil-plus"></span></button>
                                </div>
                            </div>
                        
                    </div>
                    <!----- Added for random cases : END ------------------>


                     
        
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade " id="tabs-2-tab-1">
                <div class="card-box table-responsive">

                    <table id="users" class="table table-striped table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('case.serial_number')</th>
                                <!-- <th>Select</th> -->
                                <th>@lang('case.case_id') </th>
                                <!-- <th>@lang('case.ref_id')</th> -->
                                <th>@lang('case.date') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to view the 'Case Details'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.party_details')</th>
                                <th>@lang('case.mediator') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click on 'Mediator Name' to withdraw current Mediator and/or appoint new Mediator."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <!-- <th>@lang('case.comment') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Private comments are for internal reference only. Shared comments are visible to the appointed Mediator. Comments are not visible to the parties.
                                                                                                                                                                                                                                         "><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a>
                                </th> -->
                                <th>@lang('case.session') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Schedule meeting date and time. Parties will be notified via email."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                                <th>@lang('case.action') <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Click here to upload any document/s in relation to the case."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
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
                        <div class="col-md-10">
                            <button class="blkbtn btn btn-teal waves-light waves-effect btn-sm" data-toggle="modal"
                                data-target="#withdrawModalForBulk" id="bulkCloseBtn"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk
                                Close</button>
                            <button class="blkbtn btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#uploadSupportingDocsModalForBulk" id="bulkUpload"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Upload
                                Supporting
                                Documents</button>
                            <button class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal"
                                data-target="#addSessionModelForBulk" id="bulkSession"
                                style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>"><span
                                    class="mdi mdi-pencil-plus"></span></button>
                            <button class="blkbtn btn btn-primary btn-sm" id="bulkdownload"
                                style="margin-top:10px; display:none;">Bulk Download</button>
                            <button class="blkbtn btn btn-primary btn-sm" id="downloadExcel"
                                style="margin-top:10px; display:none;">Download Invitation Delivery Sheet</button>

                        </div>

                    </div>
                </div>
            </div>
        </div>



        

    </section>



    



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
                                <th>file</th>
                                <th>Upload By</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" aria-label="Close">
                        <span>Close</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
                        <input type="submit" id="submit" name="addSupportingDocs"
                            class="btn btn-sm btn-primary mt-3">
                        <br>
                        <br>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-label="Close">
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
                        <input type="hidden" name="case_id" class="form-control">
                        <input type="hidden" name="type" class="form-control">

                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea'):</label>
                            <textarea class="form-control" name="comment" required></textarea>
                        </div>
                        <div class="row" id="commentView" style="height: 200px;overflow-x: auto">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="commentModal-close"
                            data-dismiss="modal">@lang('case.btn_close')</button>
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
                        <input type="hidden" name="case_id" class="form-control">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <select class="form-control" name="status" required>
                                <option value="">---select status---</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}">
                                    @lang('case.btn_withdrawn')</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">
                                    @lang('case.btn_resolved')</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">
                                    @lang('case.btn_unresolved')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea'):</label>
                            <textarea class="form-control" name="withdraw_comment"></textarea>
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
    <div class="modal fade" id="withdrawModalForBulk" tabindex="-1" aria-labelledby="withdrawModalLabel"
        aria-hidden="true">
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
                        <input type="hidden" name="case_id" class="form-control">
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Status:</label>
                            <select class="form-control" name="status" required>
                                <option value="">---select status---</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}">
                                    @lang('case.btn_withdrawn')</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_RESOLVED }}">
                                    @lang('case.btn_resolved')</option>
                                <option value="{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}">
                                    @lang('case.btn_unresolved')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">@lang('case.share_privet_comment_textarea'):</label>
                            <textarea class="form-control" name="withdraw_comment"></textarea>
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
                            @foreach ($users as $user)
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
    <div class="modal fade h-75" id="viewSession-modal" tabindex="-1" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                            <th scope="col">@lang('case.session_zoom_link')</th>
                            <th scope="col">@lang('case.session_note')</th>
                            <th scope="col">@lang('case.session_meeting_user')</th>
                            <th scope="col">Action</th>
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
                        <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
                    </button>
                </div>

                <form id="addSessionForm">

                    <input type="hidden" name="createdBy" id="createdByF" value="{{ Auth::id() }}">
                    <input type="hidden" name="caseId" id="caseIdF" value="">

                    <div class="custom-modal-text ">
                        <div class="form-group">
                            <label>@lang('case.session_date') :</label>
                            <input type="text" autocomplete="off" id="sessionDate" class="form-control"
                                name="sessionDate" placeholder="@lang('case.session_date_placeholder')" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_time'):</label>
                            <input type="time" id="sessionTime" autocomplete="off" class="form-control"
                                name="sessionTime" placeholder="@lang('case.session_time_placeholder')" data-validation="required">
                        
                            <div id="bookedSlots">
                            </div>
                        
                        </div>

                        <!-- Added for 2 choices : START ---------->
                        <div class="form-group">
                            <input type="radio" id="" name="zoom_choice" value="directly_zoom" checked
                                onclick="check_zoom_choice(this.value)">
                            <label for="">Schedule Directly</label><br>

                            <input type="radio" id="" name="zoom_choice" value="manually_zoom"
                                onclick="check_zoom_choice(this.value)">
                            <label for="">Manually Add Link</label>
                        </div>
                        <!-- Added for 2 choices : END ---------->



                        <div class="form-group zoom-id-section" style="display: none;">
                            <label>@lang('case.session_zoom_id') :</label>
                            <input type="text" id="zoomId" class="form-control" name="zoomId"
                                placeholder="@lang('case.session_zoom_id_placeholder')" data-validation="required">
                        </div>

                        <div class="form-group">
                            <label>@lang('case.session_note'):</label>
                            <textarea class="form-control" id="note" name="note" placeholder="@lang('case.session_note_placeholder')"
                                data-validation="required"></textarea>
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
                        <div class="form-group">
                            <label>@lang('case.session_date') :</label>
                            <input type="text" autocomplete="off" id="sessionDateForBulk"
                                class="form-control sessionDateForBulk" name="sessionDate"
                                placeholder="@lang('case.session_date_placeholder')" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_time'):</label>
                            <input type="time" id="sessionTime" autocomplete="off" class="form-control"
                                name="sessionTime" placeholder="@lang('case.session_time_placeholder')" data-validation="required">
                        </div>


                        <!-- Added for 2 choices : START ---------->
                        <div class="form-group">
                            <input type="radio" id="" name="zoom_choice" value="directly_zoom" checked
                                onclick="check_zoom_choice(this.value)">
                            <label for="">Schedule Directly</label><br>

                            <input type="radio" id="" name="zoom_choice" value="manually_zoom"
                                onclick="check_zoom_choice(this.value)">
                            <label for="">Manually Add Link</label>
                        </div>
                        <!-- Added for 2 choices : END ---------->

                        <div class="form-group zoom-id-section" style="display: none;">
                            <label>@lang('case.session_zoom_id') :</label>
                            <input type="text" id="zoomId" class="form-control" name="zoomId"
                                placeholder="@lang('case.session_zoom_id_placeholder')">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_note'):</label>
                            <textarea class="form-control" id="note" name="note" placeholder="@lang('case.session_note_placeholder')"
                                data-validation="required"></textarea>
                        </div>

                         <!------ new field for participant ---->
                         <div class="form-group">
                            
                            <label for="">Do you want session participation consent on whatsapp ?</label><br>
                            <input type="radio" id="" name="participant_whtsapp" value="0" checked><label for="">&nbsp;No</label><br>
                            <input type="radio" id="" name="participant_whtsapp" value="1"><label for="">&nbsp;Yes</label>

                        </div>
                        <!------ new field for participant ---->
                        {{-- <span>@lang('case.session_party'):</span>
                    <div class="form-group" id="sessionParty">
                    </div> --}}
                        <div class="text-center">
                            <input type="submit" name="@lang('case.session_add_title')" class="btn-sm btn-primary mt-3">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-------- Random Case Session -------------->
    <div id="addSessionModelForBulkRandom" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" class="modal-demo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Session for Random Cases</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only">Close</span>
                    </button>
                </div>

                <form id="addSessionFormForBulkRandom">

                    <input type="hidden" name="createdBy" id="createdByFR" value="{{ Auth::id() }}">
                    <input type="hidden" name="caseId" value="">

                    <div class="custom-modal-text ">
                        <div class="form-group">
                            <label>@lang('case.session_date') :</label>
                            <input type="text" autocomplete="off" id="sessionDateForBulkRandom"
                                class="form-control sessionDateForBulk" name="sessionDate"
                                placeholder="@lang('case.session_date_placeholder')" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_time'):</label>
                            <input type="time" id="sessionTime" autocomplete="off" class="form-control"
                                name="sessionTime" placeholder="@lang('case.session_time_placeholder')" data-validation="required">
                        </div>


                        <!-- Added for 2 choices : START ---------->
                        <div class="form-group">
                            <input type="radio" id="" name="zoom_choice" value="directly_zoom" checked
                                onclick="check_zoom_choice(this.value)">
                            <label for="">Schedule Directly</label><br>

                            <input type="radio" id="" name="zoom_choice" value="manually_zoom"
                                onclick="check_zoom_choice(this.value)">
                            <label for="">Manually Add Link</label>
                        </div>
                        <!-- Added for 2 choices : END ---------->

                        <div class="form-group zoom-id-section" style="display: none;">
                            <label>@lang('case.session_zoom_id') :</label>
                            <input type="text" id="zoomId" class="form-control" name="zoomId"
                                placeholder="@lang('case.session_zoom_id_placeholder')">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_note'):</label>
                            <textarea class="form-control" id="note" name="note" placeholder="@lang('case.session_note_placeholder')"
                                data-validation="required"></textarea>
                        </div>

                         <!------ new field for participant ---->
                         <div class="form-group">
                            
                            <label for="">Do you want session participation consent on whatsapp ?</label><br>
                            <input type="radio" id="" name="participant_whtsapp" value="0" checked><label for="">&nbsp;No</label><br>
                            <input type="radio" id="" name="participant_whtsapp" value="1"><label for="">&nbsp;Yes</label>

                        </div>
                        <!------ new field for participant ---->
                        {{-- <span>@lang('case.session_party'):</span>
                    <div class="form-group" id="sessionParty">
                    </div> --}}
                        <div class="text-center">
                            <input type="submit" name="@lang('case.session_add_title')" class="btn-sm btn-primary mt-3">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-------- Random Case Session -------------->


    <div id="Session-edit" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
        class="modal-demo">
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
                    <input type="hidden" name="zoomChoice" id="zoomChoice">

                    <div class="custom-modal-text ">
                        <div class="form-group">
                            <label>@lang('case.session_date') :</label>
                            <input type="text" autocomplete="off" id="editsessionDate" class="form-control"
                                name="sessionDate" placeholder="@lang('case.session_date_placeholder')" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>@lang('case.session_time'):</label>
                            <input type="time" id="editsessionTime" value="16:04" autocomplete="off"
                                class="form-control" name="sessionTime" placeholder="@lang('case.session_time_placeholder')"
                                data-validation="required">
                        </div>


                        <!-- Added for 2 choices : START ---------->
                        <!-- <div class="form-group">
                                <input type="radio" id="" name="zoom_choice" value="directly_zoom" onclick="check_zoom_choice(this.value)">
                                <label for="">Schedule Directly</label><br>

                                <input type="radio" id="" name="zoom_choice" value="manually_zoom" onclick="check_zoom_choice(this.value)">
                                <label for="">Manually Add Link</label>
                            </div> -->
                        <!-- Added for 2 choices : END ---------->




                        <div class="form-group">
                            <label>@lang('case.session_zoom_id') :</label>
                            <input type="text" id="editzoomId" class="form-control" name="zoomId"
                                placeholder="@lang('case.session_zoom_id_placeholder')" data-validation="required">
                        </div>
                        <!------- Zoom Link ------------>
                        <div class="form-group zoom-id-section" style="display: none;">
                            <label>@lang('case.session_zoom_link'):</label>
                            <!-- <label name="get_zoom_link" id="editZoomLink"></label> -->
                            <input type="text" id="editZoomLink" class="form-control" name="zoomLink"
                                data-validation="required" readonly style="background-color: #dedede">
                        </div>
                        <!------- Zoom Link ------------>
                        <div class="form-group">
                            <label>@lang('case.session_note'):</label>
                            <textarea class="form-control" id="editnote" name="note" placeholder="@lang('case.session_note_placeholder')"></textarea>
                        </div>
                        <span>@lang('case.session_party'):</span>
                        <div class="form-group" id="editsessionParty">
                        </div>
                        <div class="text-right">
                            <button type="button" class="btn-sm btn mt-3  btn-secondary"
                                data-dismiss="modal">Close</button>
                            <input type="submit" name="@lang('case.session_add_title')" class="btn-sm btn btn-primary mt-3">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!------- MOM Section ------------------------------->
    <div id="Session-mom" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Minutes of the Meeting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
                    </button>
                </div>

                <form id="MomSessionForm">
                <input type="hidden" name="MomCaseId" id="MomCaseId">
                <input type="hidden" name="MomSessId" id="MomSessId">
                <input type="hidden" name="MomSn" id="MomSn">
                    
                    <div class="custom-modal-text ">
                        
                        <div class="form-group">
                            <label>For the Applicant(s) / Initiating Party :</label>
                            <input type="text" autocomplete="off" id="ip_mom" class="form-control"
                                name="ip_mom" placeholder="" data-validation="required">
                        </div>
                        <div class="form-group">
                            <label>For the Opposite / Responding Party :</label>
                            <input type="text" id="rp_mom" value="" autocomplete="off"
                                class="form-control" name="rp_mom" placeholder=""
                                data-validation="required">
                        </div>

                        <div class="form-group">
                            <label>Mediator :</label>
                            <input type="text" id="med_name" value="" autocomplete="off"
                                class="form-control" name="med_name" placeholder=""
                                data-validation="required">
                        </div>

                        <div class="form-group">
                            <label>Minutes :</label>
                            <textarea class="form-control" id="minutes_mom" name="minutes_mom" placeholder=""></textarea>
                        </div>
                       
                        <div class="form-group">
                            <label>Next Steps :</label>
                            <!-- <label name="get_zoom_link" id="editZoomLink"></label> -->
                            <input type="text" id="next_steps" class="form-control" name="next_steps"
                                data-validation="required">
                        </div>


                        <div class="form-group">
                            <label>Share With @lang('case.session_party') :</label>
                            <div class="form-group" id="MomPartyDocs"></div>
                        </div>
                       
                        
                        <div class="text-right">
                            <button type="button" class="btn-sm btn mt-3  btn-secondary"
                                data-dismiss="modal">Close</button>
                            <input type="submit" name="submit" class="btn-sm btn btn-primary mt-3">
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!------- MOM Section ------------------------------->

    <div id="Session-delete" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true"
        class="modal-demo">
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
                    <input type="hidden" name="delZoomChoice" id="delZoomChoice">

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






    <div class="modal fade" id="coolModal">
  <div class="modal-dialog modal-lg ">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Minutes of Mediation Sessions</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span><span class="sr-only"> <span>@lang('case.btn_close')</span></span>
        </button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                <span>Close</span>
            </button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="bulkdownloadBulkcasesModal" tabindex="-1" aria-labelledby="bulkdownloadBulkcasesModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">Bulk Download</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulkdownloadBulkcasesForm" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="case_id" class="form-control">
                        <div class="form-group">
                            <label for="download-type" class="col-form-label">Select option for Bulk Download</label>
                            <select class="form-control" name="download_type" id="selectOpt" required>
                                <option value="">Select</option>
                                <option value="1">By Case ID</option>
                                <option value="2">By Reference ID</option>
                                
                            </select>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
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

    <link href="{{ url('assets/') }}/libs/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" /> 

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

    <style>
        #ui-datepicker-div {
            position: fixed !important;
            top: 176px !important;
            left: 445.5px !important;
            z-index: 1051 !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>
@endsection


@section('footer')



    <script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Datatables init -->
    <script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ url('/') }}/assets/libs/custombox/custombox.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

     <!-- <script src="{{ url('assets/') }}/libs/select2/select2.min.js"></script> -->
    <!-- <script src="{{ url('assets/') }}/libs/bootstrap-select/bootstrap-select.min.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        $(function() {

            $("#sessionDate").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy',
                onSelect: function (date, datepicker) { 
                    if (date != "") { 
                        $.ajax({
                            url: '{{ route('admin.checkSessionTime') }}',
                            dataType: "json",
                            type: "POST",
                            data: {
                                date: date
                            },
                            success: function(result) {
                               
                                var booked_html = "";
                                $.each( result.time_arr, function( key, value ) {
                                   booked_html += "<p style='margin-bottom:0px; font-size:13px; color:red;'>Session already scheduled for "+value+" on this day.</p>"
                                });
                                
                                $("#bookedSlots").html(booked_html);
                            },  


                            error: function(error) {
                               
                            }
                
                        }); 
                    } 
                } 
            });
            $("#sessionDateForBulk").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy'
            });


            $("#sessionDateForBulkRandom").datepicker({
                minDate: 0,
                dateFormat: 'dd/mm/yy'
            });

        });

        // Check for zoom choice //
        function check_zoom_choice(zoom_choice) {
            if (zoom_choice == "manually_zoom") {
                $('.zoom-id-section').show();
            } else if (zoom_choice == "directly_zoom") {
                $('.zoom-id-section').hide();
            }
        }
        // Check for zoom choice //


        $(function() {

            $('.dropify').dropify();

        });
    </script>
    <script>
        $.validate();
        // $(document).ready(function() {
        //     $('.dropify').dropify();
        // });
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
        var batch_id;

        var userTablebulk = $('#usersbulk').DataTable({
            "serverMethod": "POST",
            "sAjaxSource": '{{ route('admin.case.json', [$confirm_status, 1]) }}',
            "processing": true,
            "serverSide": true,
            "bDestroy": true,
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000],
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
            "columns": [{
                    "data": "case.id",
                    render: function(data, type, row, meta) {
                        var button = "";
                        button = button + `<input type="checkbox" class="blkchkbulkcases" data-caseid="` +
                            data+
                            `" data-refid="`+row.case.ref_id+`">`;


                        return meta.row + meta.settings._iDisplayStart + 1 + button;
                    }
                },
                // {
                //     "data": "case",
                //     render: function(data, type, row) {
                //         var button = "";
                //         button = button + `<input type="checkbox" class="blkchkbulkcases" data-caseid="` +
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
                        var button = `<p><b>Date of Creation</b><br>` + data + `</p><p><b>Date of Approval</b><br>` + row
                            .admin_approve + `</p>`;
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = ` <a href="{{ url('admin/casedetails/') }}/` + data +
                            `" target="_blank" class="btn btn-primary waves-effect  waves-light btn-sm" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                        button = button + ` <a href="{{ url('admin/updatecase/') }}/` + data +
                            `" target="_blank" class="btn btn-info waves-effect waves-light btn-sm" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                        
                        // Batch Name //
                        var batch =
                        `<p style="margin-bottom: 0px; margin-top: 5px; font-size:13px;">Batch Name</p><p style="color: blue; font-size:13px;">` +
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
                        // return  date.toLocaleDateString('en-GB');
                        button = button + `<button value="` + row.case.id + `"  data-id="` + row.case.id +
                            `" data-mediator="` + row.case.mediator_id +
                            `" data-toggle="modal" data-target="#midaterAdd" class="btn btn-info btn-sm">` +
                            data + ` </button>`;
                        if (row.case.mediator_status == 0) {
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">@lang('case.status_pending')</span>`;
                        } else if (row.case.mediator_status == 1) {
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">@lang('case.status_accepted')</span>`;
                            button = button +
                                `<br><a href="{{ url('admin/consent-and-disclosures/') }}/` + row.case
                                .id +
                                `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                            button = button + `<br><span class="">` +
                                row.mediator_create_action_date + `</span>`;
                        } else {
                            button = button +
                                `<br><span class="mediator_action" data-mediatoraction="` +
                                row.case.mediator_status + `">@lang('case.status_rejected')</span>`;
                            // button = button + `<br><span class="badge badge-danger">Date of Rejection: `+row.mediator_action_date+`</span>`;
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
                        button = button + ` <button id="Sessview` + data + `" value="` + data +
                            `"  data-id="` + data +
                            `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal" title="@lang('case.btn_session_view')" ><span class="mdi mdi-file-eye-outline"></span></button>`;
                        button = button + ` <br><button value="` + data + `"  data-id="` + data +
                            `"   class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target="#addSession-modal" title="@lang('case.btn_session_add')"><span class="mdi mdi-pencil-plus"></span></button>`;
                        return button;
                    }
                },
                {
                    "data": "case.id",
                    render: function(data, type, row) {
                        var button = "";
                        button = button + ` <button value="` + data + `"  data-id="` + data +
                            `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">Upload Supporting</button><br>`;
                        button = button + `<button value="` + data + `"  data-id="` + data +
                            `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-teal waves-light waves-effect btn-sm">@lang('case.btn_close')</button>`;
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
                                    .description + ` <br/> At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}') {
                                button = button + `<span class="">` + data[i].description +
                                    ` <br/> At : ` + data[i].created + `</span><br>`;
                            }
                            if (data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_ADMIN }}' || data[
                                    i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_REJECT_BY_MEDIATOR }}' ||
                                data[i].status ==
                                '{{ App\Models\Mediation_status_log::STATUS_WITHDRAWN }}' || data[i]
                                .status == '{{ App\Models\Mediation_status_log::STATUS_UNRESOLVED }}') {
                                button = button + `<span class="">` + data[i]
                                    .description + ` <br/> At : ` + data[i].created + `</span><br>`;
                            }

                        }
                        return button;
                    }
                },
            ],
        });

        var userTable;

        $("a.nav-link").click(function() {

            if ($(this).attr("id") == "tab1") {
                userTablebulk.ajax.reload(null, false);
                $("#selectalldir, #selectalldirBulkcases, .blkchk, .blkchkbulkcases").prop("checked", false);
                $("#downloadExcel, #downloadExcelBulkcases, #bulkSession, #bulkSessionBulkcases, #bulkdownload, #bulkdownloadBulkcases, #bulkUpload, #bulkUploadBulkcases, #bulkCloseBtn, #bulkCloseBtnBulkcases")
                    .hide();

            }
            if ($(this).attr("id") == "tab2") {
                $("#selectalldirBulkcases, #selectalldir, .blkchk, .blkchkbulkcases").prop("checked", false);

                $("#downloadExcel, #downloadExcelBulkcases, #bulkSession, #bulkSessionBulkcases, #bulkdownload, #bulkdownloadBulkcases, #bulkUpload, #bulkUploadBulkcases, #bulkCloseBtn, #bulkCloseBtnBulkcases")
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
                    "columns": [{
                            "data": "case.id",
                            render: function(data, type, row, meta) {
                                var button = "";
                                button = button +
                                    `<input type="checkbox" class="blkchk" data-caseid="` + data
                                     +
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
                        //             button = button + `<P style="font-size: 16px;"> -- </p>`;
                        //             return button;
                        //         } else {
                        //             var button = "";
                        //             button = button + `<P style="font-size: 16px;">` + data + `</p>`;
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
                                button = button + ` <a href="{{ url('admin/updatecase/') }}/` +
                                    data +
                                    `" target="_blank" class="btn btn-info waves-effect waves-light btn-sm" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                                
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
                                        if(data[i].name != null) {
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
                                                        `<span class="text-success party_name" data-inid="` +
                                                        data[
                                                            i].id + `" data-id="` + data[i].userId +
                                                        `">` + ip_name + `</span><br>`;
                                                }
                                                
                                            } else {
                                                d = d +
                                                    `<span class="text-success party_name " data-inid="` +
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
                                // return  date.toLocaleDateString('en-GB');
                                button = button + `<button value="` + row.case.id + `"  data-id="` +
                                    row.case.id +
                                    `" data-mediator="` + row.case.mediator_id +
                                    `" data-toggle="modal" data-target="#midaterAdd" class="btn btn-info btn-sm">` +
                                    data + ` </button>`;
                                if (row.case.mediator_status == 0) {
                                    button = button +
                                        `<br><span class="mediator_action" data-mediatoraction="` +
                                        row.case.mediator_status + `">@lang('case.status_pending')</span>`;
                                } else if (row.case.mediator_status == 1) {
                                    button = button +
                                        `<br><span class="mediator_action" data-mediatoraction="` +
                                        row.case.mediator_status + `">@lang('case.status_accepted')</span>`;
                                    button = button +
                                        `<br><a href="{{ url('admin/consent-and-disclosures/') }}/` +
                                        row.case
                                        .id +
                                        `" target="_blank" class="btn btn-teal waves-light waves-effect btn-xs">@lang('case.btn_disclosure')</a> `;
                                    button = button +
                                        `<br><span class="">` +
                                        row.mediator_create_action_date + `</span>`;
                                } else {
                                    button = button +
                                        `<br><span class="badge badge-danger mediator_action" data-mediatoraction="` +
                                        row.case.mediator_status + `">@lang('case.status_rejected')</span>`;
                                    // button = button + `<br><span class="badge badge-danger">Date of Rejection: `+row.mediator_action_date+`</span>`;
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
                                button = button + ` <button id="Sessview` + data + `" value="` +
                                    data +
                                    `"  data-id="` + data +
                                    `"   class="btn btn-warning waves-effect btn-sm"  data-toggle="modal" data-target="#viewSession-modal" title="@lang('case.btn_session_view')" ><span class="mdi mdi-file-eye-outline"></span></button>`;
                                button = button + `<br><button value="` + data + `"  data-id="` +
                                    data +
                                    `"   class="btn btn-pink waves-effect waves-light btn-sm" data-toggle="modal" data-target="#addSession-modal" title="@lang('case.btn_session_add')"><span class="mdi mdi-pencil-plus"></span></button>`;
                                return button;
                            }
                        },
                        {
                            "data": "case.id",
                            render: function(data, type, row) {
                                var button = "";
                                button = button + ` <button value="` + data + `"  data-id="` +
                                    data +
                                    `" data-toggle="modal" data-target="#uploadSupportingDocsModal" class="btn btn-primary waves-effect btn-sm">Upload Supporting</button><br>`;
                                button = button + `<button value="` + data + `"  data-id="` + data +
                                    `" data-toggle="modal" data-target="#withdrawModal"    class="btn btn-teal waves-light waves-effect btn-sm">@lang('case.btn_close')</button>`;
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
                                            .description + ` <br/> At : ` + data[i].created +
                                            `</span><br>`;
                                    }
                                    if (data[i].status ==
                                        '{{ App\Models\Mediation_status_log::STATUS_ACCEPTE_BY_MEDIATOR }}'
                                    ) {
                                        button = button + `<span class="">` + data[
                                                i].description +
                                            ` <br/> At : ` + data[i].created + `</span><br>`;
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
                                            .description + ` <br/> At : ` + data[i].created +
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
            userTablebulk.ajax.reload(null, false);
        });

        // function start for send one bye one ajax request 
        var insertRow = false;
        var logId = null;
        var insId = null;
        var failId = null;

        var ite = [];
        var comp = 0;
        var prc = 0;

        var ajax_request = function(item, url) {
            var deferred = $.Deferred();

            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                data: {
                    case_id: item.id,
                    _token: item.token,
                    allcids: item.allcids,
                    total_row: item.total_row,
                    log_id: logId,
                    insertRow: insId,
                    faildRow: failId,
                    fsData: item.fsData,
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
                    //     "<center>Case ID : M" +
                    //     item.cid.toString().padStart(6, "0") + " Failed.</center>"
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
                    //     "<center>Case ID : M" +
                    //     item.cid.toString().padStart(6, "0") + " Failed.</center>"
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

        var ajax_request_addSession = function(item, url) {
            var deferred = $.Deferred();

            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                data: {
                    caseId: item.id,
                    fsData: item.fsData,
                    _token: item.token,
                    allcids: item.allcids,
                    total_row: item.total_row,
                    log_id: logId,
                    insertRow: insId,
                    faildRow: failId,
                    log_type: item.log_type
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
                    //     "<center>Case ID : M" +
                    //     item.cid.toString().padStart(6, "0") + " Failed.</center>"
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
                $("#bulkCloseBtn").show();
                $("#bulkUpload").show();
                $("#bulkSession").show();
                $('#bulkdownload').show();
                $("#downloadExcel").show();

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
                $('#bulkdownload').hide();
                $("#downloadExcel").hide();

            }
        });

        $(document).on("change", ".blkchk", function() {
            var case_count = 0;
            $(".blkchk").each(function() {
                if (this.checked) {
                    case_count++;
                }
                if (this.checked) {
                    $("#bulkCloseBtn").show();
                    $("#bulkUpload").show();
                    $("#bulkSession").show();
                    $('#bulkdownload').show();
                    $("#downloadExcel").show();

                } else {
                    if (case_count == 0) {

                        $("#bulkCloseBtn").hide();
                        $("#bulkUpload").hide();
                        $("#bulkSession").hide();
                        $('#bulkdownload').hide();
                        $("#downloadExcel").hide();

                    }
                }
                if ($('#selectalldir').is(':checked')) {
                    $("#bulkCloseBtn").show();
                    $("#bulkUpload").show();
                    $("#bulkSession").show();
                    $('#bulkdownload').show();
                    $("#downloadExcel").show();

                }
                if (case_count == 0) {
                    $("#bulkCloseBtn").hide();
                    $("#bulkUpload").hide();
                    $("#bulkSession").hide();
                    $('#bulkdownload').hide();
                    $("#downloadExcel").hide();

                    $("#selectalldir").prop("checked", false);
                }
            });
        })

        $("#selectalldirBulkcases").change(function() {
            if (this.checked) {
                $("#bulkCloseBtnBulkcases").show();
                $("#bulkUploadBulkcases").show();
                $("#bulkSessionBulkcases").show();
                $('#bulkdownloadBulkcases').show();
                $("#downloadExcelBulkcases").show();

                $(".blkchkbulkcases").each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $(".blkchkbulkcases").each(function() {
                    $(this).prop("checked", false);
                });
                $("#bulkCloseBtnBulkcases").hide();
                $("#bulkUploadBulkcases").hide();
                $("#bulkSessionBulkcases").hide();
                $('#bulkdownloadBulkcases').hide();
                $("#downloadExcelBulkcases").hide();

            }
        });

        $(document).on("change", ".blkchkbulkcases", function() {
            var case_count = 0;
            $(".blkchkbulkcases").each(function() {
                if (this.checked) {
                    case_count++;
                }
                if (this.checked) {
                    $("#bulkCloseBtnBulkcases").show();
                    $("#bulkUploadBulkcases").show();
                    $("#bulkSessionBulkcases").show();
                    $('#bulkdownloadBulkcases').show();
                    $("#downloadExcelBulkcases").show();

                } else {
                    if (case_count == 0) {

                        $("#bulkCloseBtnBulkcases").hide();
                        $("#bulkUploadBulkcases").hide();
                        $("#bulkSessionBulkcases").hide();
                        $('#bulkdownloadBulkcases').hide();
                        $("#downloadExcelBulkcases").hide();

                    }
                }
                if ($('#selectalldirBulkcases').is(':checked')) {
                    $("#bulkCloseBtnBulkcases").show();
                    $("#bulkUploadBulkcases").show();
                    $("#bulkSessionBulkcases").show();
                    $('#bulkdownloadBulkcases').show();
                    $("#downloadExcelBulkcases").show();

                }
                if (case_count == 0) {
                    $("#bulkCloseBtnBulkcases").hide();
                    $("#bulkUploadBulkcases").hide();
                    $("#bulkSessionBulkcases").hide();
                    $('#bulkdownloadBulkcases').hide();
                    $("#downloadExcelBulkcases").hide();

                    $("#selectalldirBulkcases").prop("checked", false);
                }
            });
        })

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

        $("#downloadExcel, #downloadExcelBulkcases").on('click', function() {
            var cids = "";
            $(".blkchk, .blkchkbulkcases").each(function() {
                if (this.checked) {
                    if (cids == "") {
                        cids = $(this).data("caseid");
                    } else {
                        cids = cids + "," + $(this).data("caseid");
                    }
                }
            });
            $.ajax({
                type: 'post',
                url: '{{ route('admin.case.downloadLogInviation') }}',
                data: {
                    ids: cids
                },
                beforeSend: function() {
                    swal({
                        title: 'Loading...',
                        showConfirmButton: false,
                        buttons: false,
                        allowOutsideClick: false,
                    });
                },
                xhrFields: {
                    responseType: 'blob' // to avoid binary data being mangled on charset conversion
                },
                success: (blob, status, xhr) => {
                    if (status == 'nocontent') {
                        swal({
                            title: "No files available for this Case!",
                            text: "",
                            icon: "error",
                        });
                        // Msg.push("No files available for " + cid);
                    } else {
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g,
                                '');

                        }

                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {

                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);
                            if (filename) {

                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");
                                // safari doesn't support this yet
                                if (typeof a.download === 'undefined') {
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

                            URL.revokeObjectURL(downloadUrl);
                            swal({
                                text: "Excel downloaded successfully!",
                                title: "Thanks!",
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                            // Msg.push("Zip downloaded successfully for "+cid);
                        }
                    }
                },
            });
            // console.log(cids);
        })

        $('#addSessionFormForBulk').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];
            var count = 0;
            $(".blkchk, .blkchkbulkcases").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

            swal({
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal.toString() + " Cases are selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete) {
                if (willDelete) {
                    var cids = null;
                    var idarr = [];
                    var ctcnt = 0;
                    var result = {};

                    $(".blkchk, .blkchkbulkcases").each(function() {
                        if (this.checked) {
                            ctcnt++;
                            if (cids == null) {
                                cids = $(this).data("caseid");
                            } else {
                                cids = cids + "," + $(this).data("caseid");
                            }
                        }
                    });

                    $(".blkchk, .blkchkbulkcases").each(function() {
                        if (this.checked) {

                            $('#addSessionFormForBulk').find('input[name="caseId"]').val(id);
                            var id = $(this).data("caseid");

                            var csrf = document.querySelector('meta[name="csrf-token"]').content;

                            $.each($('#addSessionFormForBulk').serializeArray(), function() {
                                result[this.name] = this.value;
                            });

                            idarr.push({
                                id: $(this).data("caseid"),
                                token: csrf,
                                allcids: cids,
                                total_row: ctcnt,
                                fsData: result,
                                log_type: "add Session",
                            });

                        }
                    });


                    var burl = '{{ route('admin.case.addSession') }}';

                    console.log(idarr);
                    return false;
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

                                    return ajax_request_addSession(item, burl);

                                });
                                return looper;

                            })
                        )
                        .then(function() {
                            swal.close();
                            // $("#myModalcc").hide();
                            $("#messccclose").append(
                                '<br><center><a href="{{ route('admin.case.ongoingrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                    // $(".blkchk").each(function() {
                    //     if (this.checked) {
                    //         var id = $(this).data("caseid");
                    //         $('#addSessionFormForBulk').find('input[name="caseId"]').val(id);
                    //         $.ajax({
                    //             type: 'post',
                    //             url: '{{ route('admin.case.addSession') }}',
                    //             data: $('#addSessionFormForBulk').serialize(),
                    //             beforeSend: function() {
                    //                 $('#addSessionModelForBulk').modal("hide");

                    //                 swal({
                    //                     title: 'Loading...',
                    //                     showConfirmButton: false,
                    //                     buttons: false,
                    //                     allowOutsideClick: false,
                    //                 });
                    //             },
                    //             success: function() {
                    //                 // alert('form was submitted');
                    //                 swal("session created!", {
                    //                     icon: "success",
                    //                 }).then(function() {
                    //                     location.reload();
                    //                 });
                    //                 // $('#addSession-modal').modal("hide");
                    //             }
                    //         });
                    //     }

                    // });
                } else {
                    swal("@lang('case.request_canseled')").then(function() {
                        location.reload();
                    });
                }
            });

        });

        // Random case create session //
        $('#addSessionFormForBulkRandom').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];
            var count = 0;
            // alert(444);
            // console.log($('#rCases').val());
            // return false;
            // $(".blkchk, .blkchkbulkcases").each(function() {
            //     if (this.checked) {
            //         count++;
            //     }
            //     withdrawcount.push(count);
            // });

            // var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

            swal({
                title: "@lang('case.are_you_sure')",
                text: $('#rCases').val().length + " Cases are selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete) {
                if (willDelete) {
                    var cids = null;
                    var idarr = [];
                    var ctcnt = 0;
                    var result = {};

                    // $(".blkchk, .blkchkbulkcases").each(function() {
                    //     if (this.checked) {
                    //         ctcnt++;
                    //         if (cids == null) {
                    //             cids = $(this).data("caseid");
                    //         } else {
                    //             cids = cids + "," + $(this).data("caseid");
                    //         }
                    //     }
                    // });

                    // $(".blkchk, .blkchkbulkcases").each(function() {
                    //     if (this.checked) {

                    //         $('#addSessionFormForBulk').find('input[name="caseId"]').val(id);
                    //         var id = $(this).data("caseid");

                    //         var csrf = document.querySelector('meta[name="csrf-token"]').content;

                    //         $.each($('#addSessionFormForBulk').serializeArray(), function() {
                    //             result[this.name] = this.value;
                    //         });

                    //         idarr.push({
                    //             id: "",
                    //             token: csrf,
                    //             allcids: $('#rCases').join(''),
                    //             total_row: $('#rCases').lengthS,
                    //             fsData: result,
                    //             log_type: "add Session",
                    //         });

                    //     }
                    // });

                    var csrf = document.querySelector('meta[name="csrf-token"]').content;
                    var burl = '{{ route('admin.case.addSession') }}';
                    $.each($('#addSessionFormForBulkRandom').serializeArray(), function() {
                                result[this.name] = this.value;
                            });
                           
                           // alert($("#rCases").select2().find(":selected").data("id"));
                        var al_id = [];

                        $.each($('#rCases').val(), function (key, val) {
                            var id_int = val.replace('M0', '');
                            var final_id = parseInt(id_int);
                            al_id.push(final_id);
                        });
                       // alert(al_id.join());
                    $.each($('#rCases').val(), function (key, val) {
                        //alert(key + val);
                       // alert($('#rCases').attr('myTag'));
                        var id_int = val.replace('M0', '');
                        var final_id = parseInt(id_int);

                       // al_id.push(final_id);
                        //alert(al_id);
                        // var all_cids = $('#rCases').val().replace('M0', '');
                        // all_cids = all_cids.join();
                        idarr.push({
                            id: final_id,
                            token: csrf,
                            allcids: al_id.join(),
                            total_row: $('#rCases').val().length,
                            fsData: result,
                            log_type: "add Session",
                        });
                    });

                    
                    // console.log(idarr);
                    // return false;
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

                                    return ajax_request_addSession(item, burl);

                                });
                                return looper;

                            })
                        )
                        .then(function() {
                            swal.close();
                            // $("#myModalcc").hide();
                            $("#messccclose").append(
                                '<br><center><a href="{{ route('admin.case.ongoingrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                    
                } else {
                    swal("@lang('case.request_canseled')").then(function() {
                        location.reload();
                    });
                }
            });

        });
        // Random case create session //

        $('#withdrawFormForBulk').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];
            var count = 0;
            $(".blkchk, .blkchkbulkcases").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);
            swal({
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal.toString() + " Cases are selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var cids = null;
                    var Status = $('#status_id').val();

                    var idarr = [];
                    var ctcnt = 0;
                    var result = {};


                    $(".blkchk, .blkchkbulkcases").each(function() {
                        if (this.checked) {
                            ctcnt++;
                            if (cids == null) {
                                cids = $(this).data("caseid");
                            } else {
                                cids = cids + "," + $(this).data("caseid");
                            }
                        }
                    });
                    $(".blkchk, .blkchkbulkcases").each(function() {

                        if (this.checked) {

                            $('#withdrawModalForBulk').find('input[name="caseId"]').val(id);
                            var id = $(this).data("caseid");
                            var csrf = document.querySelector('meta[name="csrf-token"]').content;

                            $.each($('#withdrawFormForBulk').serializeArray(), function() {
                                result[this.name] = this.value;
                            });
                            console.log(result);
                            idarr.push({
                                id: $(this).data("caseid"),
                                token: csrf,
                                allcids: cids,
                                total_row: ctcnt,
                                fsData: result,
                                log_type: "Bulk withdraw ",
                            });
                        }
                    });
                    var burl = "{{ route('admin.case.withdraw') }}";
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
                            // $("#myModalcc").hide();
                            $("#messccclose").append(
                                '<br><center><a href="{{ route('admin.case.ongoingrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                    // $(".blkchk").each(function() {
                    //     if (this.checked) {
                    //         var id = $(this).data("caseid");
                    //         $('#withdrawModalForBulk').find('.modal-body input[name="case_id"]')
                    //             .val(id);

                    //         $.ajax({
                    //             type: 'post',
                    //             url: '{{ route('admin.case.withdraw') }}',
                    //             data: $('#withdrawFormForBulk').serialize(),
                    //             beforeSend: function() {
                    //                 swal({
                    //                     title: 'Loading...',
                    //                     showConfirmButton: false,
                    //                     buttons: false,
                    //                 });
                    //             },
                    //             success: function() {
                    //                 // alert('form was submitted');
                    //                 userTable.ajax.reload(null, false);
                    //                 swal("@lang('case.status_change_successfully')", {
                    //                     icon: "success",
                    //                 }).then(function() {
                    //                     location.reload();
                    //                 });
                    //                 $('#withdrawModalForBulk').modal("hide");
                    //             }
                    //         });
                    //     }
                    // });
                } else {
                    swal("@lang('case.request_canseled')");
                }
            });
            return false;
        });

        $(document).on('submit', "#MidaterForm", function() {
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
                            if (typeof userTable !== "undefined") {
                                userTable.ajax.reload(null, false);
                            } else {
                                userTablebulk.ajax.reload(null, false);
                            }
                            $('#midaterAdd').modal("hide");
                        } else {
                            swal("Please Select Mediator", {
                                icon: "error",
                            }).then(function() {
                                location.reload();
                            });
                        }
                    });
                } else {
                    swal("@lang('case.cansel_confirm_request')");
                }
            });
            return false;
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
                userTablebulk.ajax.reload(null, false);
            }
        });

        $('#uploadSupportingDocsModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var data = button.parent().parent().find(".party_name");
            var mediatorData = button.parent().parent().find(".mediator_action");
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

        $('#uploadFormModalForBulk').on('submit', function(e) {
            e.preventDefault();
            var withdrawcount = [];
            var count = 0;
            $(".blkchk, .blkchkbulkcases").each(function() {
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


                    $(".blkchk, .blkchkbulkcases").each(function() {
                        if (this.checked) {
                            ctcnt++;
                            if (cids == null) {
                                cids = $(this).data("caseid");
                            } else {
                                cids = cids + "," + $(this).data("caseid");
                            }
                        }
                    });


                    $(".blkchk, .blkchkbulkcases").each(function() {
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

                    // $.ajax({
                    //     type: 'POST',
                    //     url: '{{ route('admin.case.storeMultiFile') }}',
                    //     data: formData,
                    //     cache: false,
                    //     contentType: false,
                    //     processData: false,
                    //     dataType: 'json',
                    //     beforeSend: function() {
                    //         $('#uploadSupportingDocsModalForBulk').modal(
                    //             "hide");

                    //         swal({
                    //             title: 'Loading...',
                    //             showConfirmButton: false,
                    //             buttons: false,
                    //             allowOutsideClick: false,
                    //         });
                    //     },
                    //     success: (data) => {
                    //         //this.reset();
                    //         swal("Files has been uploaded!", {
                    //             icon: "success",
                    //         }).then(function() {
                    //             location.reload();
                    //         });
                    //         $("#uploadSupportingDocsModalForBulk").modal(
                    //             "hide");
                    //     },
                    //     error: function(data) {
                    //         //alert(data.responseJSON.errors.files[0]);
                    //         console.log(data);
                    //     }
                    // });



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
                                '<br><center><a href="{{ route('admin.case.ongoingrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                } else {
                    swal("@lang('case.request_canseled')");
                }
            });

        });

        $('#bulkdownload').on('click', function(e) {
            e.preventDefault();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var withdrawcount = [];
            var count = 0;
            $(".blkchk, .blkchkbulkcases").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

            swal({
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal.toString() + " Cases selected 123" + '<form role="form" id="contact-form" method="post"><div class="control-group"><label for="friend_name">Your friend\'s name</label><input type="text" placeholder="Your friend\'s full name" id="friend_name" name="friend_name" required></div><div class="control-group"><label for="email">Email</label><input type="email" placeholder="Your friend\'s Email" id="email" name="email" required></div></form>',
                icon: "warning",
                buttons: true,
                dangerMode: true,
                
            }).then(function(willDelete) {
                if (willDelete) {
                    var cid = "";
                    var refid = "";
                    $(".blkchk, .blkchkbulkcases").each(function() {
                        if (this.checked) {
                            if (cid == "") {
                                cid = $(this).data("caseid");
                                refid = $(this).data("refid");
                            } else {
                                cid = cid + "," + $(this).data("caseid");
                                refid = refid + "," + $(this).data("refid");
                            }
                        }
                    });
                    $.ajax({
                        url: '{{ route('admin.case.downloadfilebulk') }}',
                        type: 'post',
                        data: {
                            allcid: cid,
                            allrefid: refid,
                            _token: csrf
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: (blob, status, xhr) => {
                            if (status == 'nocontent') {
                                swal({
                                    title: "No files available for this Case!",
                                    text: "",
                                    icon: "error",
                                });
                            } else {
                                var filename = "";
                                var disposition = xhr.getResponseHeader('Content-Disposition');
                                if (disposition && disposition.indexOf('attachment') !== -1) {
                                    var filenameRegex =
                                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                    var matches = filenameRegex.exec(disposition);
                                    if (matches != null && matches[1]) filename = matches[1]
                                        .replace(/['"]/g, '');

                                }

                                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                    window.navigator.msSaveBlob(blob, filename);
                                } else {

                                    var URL = window.URL || window.webkitURL;
                                    var downloadUrl = URL.createObjectURL(blob);
                                    if (filename) {

                                        var a = document.createElement("a");
                                        if (typeof a.download === 'undefined') {
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

                                    URL.revokeObjectURL(downloadUrl);
                                    swal({
                                        text: "Zip downloaded successfully!",
                                        title: "Thanks!",
                                        icon: "success",
                                    }).then(function() {
                                        location.reload();
                                    });
                                }
                            }
                        },
                    });
                }
            });
        });



        // for refid and case id submit //
        $('#bulkdownloadBulkcasesForm').on('submit', function(e) {
            e.preventDefault();
            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var withdrawcount = [];
            var count = 0;

            var select_option = $('#selectOpt').val();
            
            $(".blkchk, .blkchkbulkcases").each(function() {
                if (this.checked) {
                    count++;
                }
                withdrawcount.push(count);
            });

            var withdrawcountTotal = Math.max.apply(Math, withdrawcount);

            swal({
                title: "@lang('case.are_you_sure')",
                text: withdrawcountTotal.toString() + " Cases selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete) {
                if (willDelete) {
                    var cid = "";
                    var refid = "";
                    $(".blkchk, .blkchkbulkcases").each(function() {
                        if (this.checked) {
                            if (cid == "") {
                                cid = $(this).data("caseid");
                                refid = $(this).data("refid");
                            } else {
                                cid = cid + "," + $(this).data("caseid");
                                refid = refid + "," + $(this).data("refid");
                            }
                        }
                    });

                    // if(select_option == 1){
                    //     var arr =  {
                    //         allcid: cid,
                    //         _token: csrf  
                    //     }
                    // } else {
                    //     var arr =  {
                    //         allrefid: refid,
                    //         _token: csrf  
                    //     }
                    // }
                    $.ajax({
                        url: '{{ route('admin.case.downloadfilebulk') }}',
                        type: 'post',
                        data: {
                            allcid: cid,
                            allrefid: refid,
                            select_option: select_option,
                            _token: csrf
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: (blob, status, xhr) => {
                            if (status == 'nocontent') {
                                swal({
                                    title: "No files available for this Case!",
                                    text: "",
                                    icon: "error",
                                });
                            } else {
                                var filename = "";
                                var disposition = xhr.getResponseHeader('Content-Disposition');
                                if (disposition && disposition.indexOf('attachment') !== -1) {
                                    var filenameRegex =
                                        /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                                    var matches = filenameRegex.exec(disposition);
                                    if (matches != null && matches[1]) filename = matches[1]
                                        .replace(/['"]/g, '');

                                }

                                if (typeof window.navigator.msSaveBlob !== 'undefined') {
                                    window.navigator.msSaveBlob(blob, filename);
                                } else {

                                    var URL = window.URL || window.webkitURL;
                                    var downloadUrl = URL.createObjectURL(blob);
                                    if (filename) {

                                        var a = document.createElement("a");
                                        if (typeof a.download === 'undefined') {
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

                                    URL.revokeObjectURL(downloadUrl);
                                    swal({
                                        text: "Zip downloaded successfully!",
                                        title: "Thanks!",
                                        icon: "success",
                                    }).then(function() {
                                        location.reload();
                                    });
                                }
                            }
                        },
                    });
                }
            });

        });
        // for refid and case id submit //

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
                            $('#commentForm')[0].reset();
                            swal("@lang('case.comment_save_successfully')", {
                                icon: "success",
                            }).then(function() {
                                //    location.reload();
                            });
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
                    userTablebulk.ajax.reload(null, false);
                }

            });
            return false;
        });
        $('#withdrawForm').on('submit', function(e) {
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
                        url: '{{ route('admin.case.withdraw') }}',
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

                            swal("@lang('case.status_change_successfully')", {
                                icon: "success",
                            }).then(function() {
                                location.reload();
                            });
                            $('#withdrawModal').modal("hide");
                            if (typeof userTable !== "undefined") {
                                userTable.ajax.reload(null, false);
                            } else {
                                userTablebulk.ajax.reload(null, false);
                            }
                        }
                    });
                } else {
                    swal("@lang('case.request_canseled')");
                }
            });
            return false;
        });
        $('#midaterAdd').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var recipient = button.data('id');
            var mediator = button.data('mediator');
            var modal = $(this)
            modal.find('.modal-body input[name="id"]').val(recipient);
            // modal.find('.modal-body select[name="midater"]').val(mediator);
            var users = <?php echo json_encode($users); ?>;
            var htmlData =
                "<select class='form-control' name='midater'  required><option value=''>@lang('case.form_select_mediator')</option>";
            users.forEach(function(item, index) {
                if (item.isActive) {
                    if (mediator === item.id) {
                        htmlData += "<option value='" + item.id +
                            "' disabled style='background-color:#d6d2d2'>" + item.first_name + " " + item
                            .last_name + " - " + item.organization + "</option>";
                    } else {
                        htmlData += "<option value='" + item.id + "'>" + item.first_name + " " + item
                            .last_name + " - " + item.organization + "</option>";
                    }
                }
            });
            htmlData += "</select>";
            document.getElementById("mediatorList").innerHTML = htmlData;

        });
        $('#addSession-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var data = button.parent().parent().find(".party_name");
            $("#sessionParty").html("");
            data.each(function() {
                var party_id = $(this).data("inid")
                var party_name = $(this).text()
                var text = `<div class="form-check">
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
                    swal("@lang('case.session_created')", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                    $('#addSessionForm')[0].reset();
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

        $('#Session-edit').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            $("#editsessionParty").html("");
            var editId = button.data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.case.SendforEditSession') }}",
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
                        $('#zoomChoice').attr('value', response.zoom_link_choice);
                        $('#editsessionDate').attr('value', response.session_date.substring(0, 10));
                        var convertTime = convertTime12to24(response.session_date.substring(11));
                        $('#editsessionTime').attr('value', convertTime);
                        $('#editzoomId').attr('value', response.zoom_id);
                        $('#editZoomLink').attr('value', response.zoom_link);
                        $('#editnote').val(response.note);


                        if (response.zoom_link_choice == "manual") {
                            $('#editzoomId').removeAttr('readonly');
                            $('#editzoomId').css('background', '#ffffff');
                            $('.zoom-id-section').hide();
                        } else if (response.zoom_link_choice == "direct") {
                            $('#editzoomId').attr('readonly', 'readonly');
                            $('#editzoomId').css('background', '#dedede');
                            $('.zoom-id-section').show();
                        }
                        var session_party = response.session_party_ids;

                        data.each(function() {
                            var party_id = $(this).data("inid")
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
        $('#Session-delete').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var delid = button.data('id');
            var delZoomChoice = button.data('zoom-choice');
            $("#deleteSessId").val(delid);
            $("#delZoomChoice").val(delZoomChoice);
        });

        $('#Session-delete-reason').on('show.bs.modal', function(event) {
            $("#view_reason").text("");
            var button = $(event.relatedTarget);
            var reason = button.data('reason');
            $("#view_reason").text(reason);
        });


        // MOM template show 
        $('#Session-mom').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var SessId = button.data('id');
            var CaseId = button.data('caseid');
            var Sn = button.data('sn');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.case.ShowMomSessionData') }}",
                data: {
                    id: SessId,
                    caseid : CaseId
                },
                dataType: "JSON",
                success: function(response) {
                    
                    var data = response.party_array;
                    $('#MomCaseId').attr('value', CaseId);
                    $('#MomSessId').attr('value', SessId);
                    $('#MomSn').attr('value', Sn);
                    $('#ip_mom').attr('value', response.ip_name);
                    $('#rp_mom').attr('value', response.rp_name);
                    
                    $('#minutes_mom').val(response.minutes);
                    $('#next_steps').attr('value', response.next);

                    $('#med_name').attr('value', response.mediator);


                    // var med_text = `<div class="form-check">
                    // <input type="checkbox" value="` + response.mediator_id + `" class="form-check-input" name="docs_party_ids" id="party">
                    // <label class="form-check-label" for="party">` + response.mediator + `</label>
                    // </div>`;
                    // $("#MomMediatorDocs").append(med_text);
                    $("#MomPartyDocs").empty();
                    $.each(data, function(index, elm) {

                            var party_id = elm.id;
                            var party_name = elm.name;

                            
                            if (response.selected_id != null) {
                                if(response.selected_id.includes(party_id)){
                                    var checked = "checked";
                                }
                                
                            } else {
                               
                                var checked = "";
                            }
                            var text = `<div class="form-check">
                            <input type="checkbox" ` + checked + ` value="` + party_id + `" class="form-check-input" name="docs_party_ids[]" id="party">
                            <label class="form-check-label" for="party">` + party_name + `</label>
                        </div>`;
                            $("#MomPartyDocs").append(text);
                    });
                }
            });
        });
        // MOM template show 

        $("#UpdateSessionForm").on('submit', function(e) {
            e.preventDefault();
            var formSet = $('#UpdateSessionForm').serialize();
            //console.log(formSet);
            $.ajax({
                type: "POST",
                url: "{{ route('admin.case.UpdateSession') }}",
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
                    $('#Session-edit').modal('hide');
                    swal("Session Updated Successfully!", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });
                }
            });
        })


        // MOM form submit 
        $("#MomSessionForm").on('submit', function(e) {
            e.preventDefault();
            var formSet = $('#MomSessionForm').serialize();
            
            

            $.ajax({
                type: "POST",
                url: "{{ route('admin.case.MomFormSubmit') }}",
                data: formSet,
                dataType: "JSON",
                beforeSend: function() {
                    $('#viewSession-modal').modal("hide");

                    swal({
                        title: 'Loading...',
                       // showConfirmButton: false,
                        buttons: false,
                        //allowOutsideClick: false,
                    });
                },
                success: function(response) {
                    // console.log(response);
                    // return false;
                    $('#Session-mom').modal('hide');
                     swal({
                         title: "Minutes Successfully Submitted !",
                         icon: "success",
                         buttons: true,
                      }).then(function() {
                       // location.reload();

                       //setTimeout(function () {
                       var case_id = $('#MomCaseId').val();
                      
                       $("#coolModal").modal('show');
                       previewMom(case_id, response.file, response.path, response.preview.original.html);
                       //}, 2500);
                    });
                },
                
            });
        });


        $(document).on('click', '#sessiondeleteform', function() {
            var Sessid = $("#deleteSessId").val();
            var reason = $("#reasondelete").val();
            var delZoomChoice = $("#delZoomChoice").val();
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
                        url: "{{ route('admin.case.DeleteSession') }}",
                        data: {
                            SessId: Sessid,
                            reason: reason,
                            delZoomChoice: delZoomChoice
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


        function previewMom(caseid, file_name, path, preview_html){
            $("#coolModal").modal('show');
            $('#coolModal .modal-body').html(preview_html);
  
        }




        $(document).ready(function () {   
            $('#rCases').select2({
              
            multiple: true,
              placeholder: "Enter Random cases",
              closeOnSelect: false,
              minimumInputLength: 1
        });     
            $("#randomCase").change(function () {
                if ($("#randomCase").is(":checked")) {
                    $('#randomCaseSec').show();

                    var csrf = document.querySelector('meta[name="csrf-token"]').content;


                   


                            var tableId = 'randomCaseSec';     

                           

                    // ajax call 
                    $.ajax({
                        url: '{{ route('admin.getAllCaseIDList') }}',
                        method: "get",
                        data: {
                            _token: csrf
                        },
                        success: function(resp) {
                            $('#rCases').empty();
                            var result = $.parseJSON(resp);
                            
                            // if(resp.user_data != ""){
                            var option_html = "";
                               // var option_html = "<option value='0'>Select Case ID</option>";
                                $(result.case_arr).each(function( index, element ) { 

                                    option_html += "<option value='"+element.full+"' name='random_caseid' data-id='"+element.id+"' myTag='"+element.id+"'>"+element.full+"</option>" ; 
                                });
                            // } else {
                            //     var option_html = "<option value='0'>No Sub Users</option>";  
                            // }

                            
                            $('#rCases').html(option_html);
                             
                             
                        },

                        error: function(err) {
                            console.log(err);
                        },
                    });
                    // ajax call 


                    setTimeout(function () {

                    // $("#rCases").on('select2:open', function () {
                    // console.log('clicked---');


                    // var page_name = '{{ route('admin.getAllCaseIDList') }}';
                    // var fromvalue = "";
                    // var toValue = "";
                    // var actionvalue = "";

                    // getExceptDropdownRenderRandomCase(
                    //     page_name,
                    //     tableId,
                    //     type,
                    //     ".blkdirtorandom",
                    //     fromvalue,
                    //     toValue,
                    //     "Enter Random cases 456",
                    //     actionvalue,
                    //     csrf
                    // );
                    // multiSelectExept(tableId);
                    // });

                    $("#" + tableId + " .blkdirtorandom")
        //.empty()
        .select2({
         // multiple: true,
        //  placeholder: "Enter Random cases",
         // closeOnSelect: false,
        //   minimumInputLength: 1
        });






                     multiSelectExept(tableId)
                    },100);
                }
            });        
        });




        function multiSelectExept(tableId) {
        //    alert(tableId);
                var current_pin = [];
                $("body").on(
                    "paste",
                    "#" + tableId + " .select2-search__field",
                    function (e) {
                       // alert('in');
                        //alert(tableId);
                    var pastedData = e.originalEvent.clipboardData.getData("text").trim();
                    tokens = pastedData.split(/\r\n|\r|\n/g);

                    console.log(tokens);

                    /* print_console('pastedData', pastedData) */
                    /* print_console('tokens', tokens) */
                    $("#" + tableId + " .blkdirtorandom option").each(
                        function () {
                            current_pin.push(this.value);
                        }
                    );

                    found_pin = tokens.filter((value) => current_pin.includes(value));
                    console.log(found_pin);
                     //found_pin = ['39414', '39415']
                    /* print_console('found_pin', found_pin) */
                    /* print_console('current_pin', current_pin) */
                    //print_console('current input', $('#pincode_div').find('input').val());
                    $(".select2-search__field").val("");
                    // $('#pincode_div').find('input').attr('class')
                    $("#" + tableId + " .blkdirtorandom").val("");
                    $("#" + tableId + " .blkdirtorandom")
                        .val(found_pin)
                        .trigger("change");
                    }
                );
            }


            function getExceptDropdownRenderRandomCase(
                page_name,
                tableId,
                type,
                dropdownClass,
                fromvalue = "",
                toValue = "",
                placeholderText = "From",
                actionvalue = 0,
                csrf
                ) {
                var idarr = [];
                exceptDropDown = [];
                $("#filter_check1").show();
                setTimeout(() => {
                    // $(".singlecs").each(function () {
                    //   idarr.push($(this).data('firstid'));
                    //   idarr.push($(this).data('searchvalue'));
                    //   idarr.push($(this).data('lastid'));
                    // });

                    // if (tableId == "ongoing") {
                    //   $(".singlecs").each(function () {
                    //     idarr.push($(this).data("firstid"));
                    //     idarr.push($(this).data("searchvalue"));
                    //     idarr.push($(this).data("green"));
                    //     idarr.push($(this).data("blue"));
                    //     idarr.push($(this).data("lastid"));
                    //   });
                    // } else if (tableId == "closed") {
                    //   $(".singlecsclose").each(function () {
                    //     idarr.push($(this).data("firstid"));
                    //     idarr.push($(this).data("searchvalue"));
                    //     idarr.push($(this).data("green"));
                    //     idarr.push($(this).data("blue"));
                    //     idarr.push($(this).data("lastid"));
                    //   });
                    // } else if (tableId == "adminreview") {
                    //   $(".blkchk").each(function () {
                    //     idarr.push($(this).data("firstid"));
                    //     idarr.push($(this).data("searchvalue"));
                    //     idarr.push($(this).data("lastid"));
                    //   });
                    // }

                    // // console.log(batch_name);
                    // // console.log(idarr);

                    // // var Fval = idarr[0];
                    // var searchValue = idarr[1];
                    // var greenmark = idarr[2];
                    // var bluemark = idarr[3];
                    // // var Lval = idarr[idarr.length - 1];

                    // var batch_name = $("#batch_name_change").val();
                    // var from_date = $("#from_date").val();
                    // var to_date = $("#to_date").val();
                    // var arb_name = $("#arbitrator_change").val();
                    // var org_name = $("#organisation_change").val();
                    // var current_status = $("#status_change").val();

                    // if (!toValue) {
                    //   var Fval = idarr[0];
                    // } else {
                    //   var Fval = toValue.replace(/^A0+|^A+/, "");
                    // }
                    // var searchValue = idarr[1];
                    // if (!fromvalue) {
                    //   var Lval = idarr[idarr.length - 1];
                    // } else {
                    //   var Lval = fromvalue.replace(/^A0+|^A+/, "");
                    // }

                    console.log("OK");

                    $.ajax({
                                        url: '{{ route('admin.getAllCaseIDList') }}',
                                        method: "get",
                                        data: {
                                            _token: csrf
                                        },
                                        success: function(resp) {
                                            var response = $.parseJSON(resp);
                                    $("#" + tableId +  dropdownClass)
                        .empty()
                        .select2({
                            multiple: true,
                            placeholder: placeholderText,
                            closeOnSelect: false,
                        });
                        var selectOptions = "";

                        $.each(response, function (indexInArray, valueOfElement) {

                        // if(exceptDropDown.length > 0 && exceptDropDown.length == 1){
                        //   $("#filter_check1").hide();
                        //   $(".filter_check").show();
                        // }else if(exceptDropDown.length == 0){
                        //   exceptDropDown.push(valueOfElement.caseid);
                        // }
                        // $("#" + tableId + "_filter " + dropdownClass).append(
                        //   '<option class="caseidexcept">' +
                        //   valueOfElement.caseid +
                        //   "</option>"
                        // );
                        selectOptions += '<option class="caseidexcept">' + valueOfElement.full + "</option>";
                        });

                        $("#" + tableId  + dropdownClass).empty().append(selectOptions);

                        $("#filter_check1").hide();
                        $(".filter_check").show();
                                            
                                            
                                        },

                                        error: function(err) {
                                            console.log(err);
                                        },
                                    });
                }, 100);
                }
        
    </script>
@endsection
