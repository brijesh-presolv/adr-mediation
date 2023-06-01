@extends('mediator.layouts.app')
@section('title', 'New request')

@section('breadcrumb')
    <!-- start page title -->
    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
    <li class="breadcrumb-item"><a href="javascript: void(0);">New request </a></li>
    <!-- end page title -->
@endsection

@section('content')

@section('pageTitleOnDashboard', 'New request')

{{-- @section('pageTitleOnDashboard')
<h4 class="page-title">New Request</h4>
@endsection --}}

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


<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table id="request" class="table table-striped table-bordered dt-responsive nowrap"
                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <!-- <th>Select</th> -->
                        <th>Case ID</th>
                        <th>Ref ID</th>
                        <th>Date <a href="#" data-toggle="tooltip" title=""
                                        data-original-title="Date and time of raising the 'Request for Mediation'."><i
                                            class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>Party Details</th>
                        <!-- <th>Comments</th> -->
                        <th>Action <a href="#" data-toggle="tooltip" title=""
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
                    <button class="btn btn-success btn-sm blkbtn" id="bulkAcceptBtn"
                        style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk Accept</button>
                    <button class="btn btn-danger btn-sm blkbtn" id="bulkRejectBtn"
                        style="margin-top:10px; display:none;" data-arb="<?= Auth::user()->id ?>">Bulk Reject</button>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptModalLabel">Accept Request To </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="acceptForm">
                {{-- {{dd($mediationDetails)}} --}}
                <input type="hidden" name="mediation_case_id">
                <input type="hidden" name="status" value="1">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 text-center">

                            <h5>Mediator’s Consent and Disclosures by {{ $mediationDetails->first_name }}
                                {{ $mediationDetails->last_name }}</h5><br>
                            {{-- <p>See Rule 6 of Section 3 of Presolv360’s Dispute Resolution Rules (“Rules”) read with the
                                Arbitrators’ and Mediators’ Code of Conduct and Disclosure Rules (“Code”)</p> --}}
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-hover table-bordered table-striped">
                                <tr>
                                    <th colspan='2' class="text-center">Details of the Mediator</th>
                                </tr>
                                <tr>
                                    <td width='50%'>
                                        <p>Email</p>
                                    </td>
                                    <td width='50%'><a href="mailto:admin@presolv360.com">admin@presolv360.com</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td width='50%'>
                                        <p>Experience</p>
                                    </td>
                                    <td width='50%'>
                                        <textarea class="form-control" cols="150" name="particulars1" id="particulars1" rows="3">{{ isset($mediationDetails->experience) ? $mediationDetails->experience : '' }}</textarea>
                                    </td>
                                </tr>
                            </table>
                            {{-- <p><u>Details of the Dispute</u></p>

                            <table id="partyDetails" class="table table-hover table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <td>Initiating Party</td>
                                        <td>Responding Party</td>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>

                            <p>Details of Dispute as per Initiating Party: </p>
                            <p id="issueModal"></p> --}}

                        </div>
                        <div class="col-lg-12">
                            <table class="table table-hover table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th>Consent</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>I accept and consent to act as a mediator in the captioned dispute </td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent1"
                                                        id="consent1yes" value="1" checked>
                                                    <label class="form-check-label" for="consent1yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent1"
                                                        id="consent1no" value="0">
                                                    <label class="form-check-label" for="consent1no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I am qualified, possess the required competence, knowledge and expertise,
                                            and have sufficient time to be able to conduct the mediation proceedings
                                            within the time limits prescribed</td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent2"
                                                        id="consent2yes" value="1" checked>
                                                    <label class="form-check-label" for="consent2yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent2"
                                                        id="consent2no" value="0">
                                                    <label class="form-check-label" for="consent2no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I shall be, and remain, independent and neutral throughout the proceedings
                                            i.e. from beginning to end and ensure that my words, manner, attitude, body
                                            language and process management reflects an impartial and even-handed
                                            approach</td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent3"
                                                        id="consent3yes" value="1" checked>
                                                    <label class="form-check-label" for="consent3yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent3"
                                                        id="consent3no" value="0">
                                                    <label class="form-check-label" for="consent3no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I shall conduct the mediation proceedings in a fair and impartial manner,
                                            and endeavour to provide a procedurally fair process in which each party is
                                            given an adequate opportunity to participate </td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent4"
                                                        id="consent4yes" value="1" checked>
                                                    <label class="form-check-label" for="consent4yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent4"
                                                        id="consent4no" value="0">
                                                    <label class="form-check-label" for="consent4no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I shall maintain utmost confidentiality of all matters relating to mediation
                                            proceedings, including all documents, records, and communications, during as
                                            well as after its completion</td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent5"
                                                        id="consent5yes" value="1" checked>
                                                    <label class="form-check-label" for="consent5yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent5"
                                                        id="consent5no" value="0">
                                                    <label class="form-check-label" for="consent5no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Disclosures</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- <tr>
                                        <td>Experience</td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" cols="150" name="particulars1" id="particulars1" rows="3">{{ isset($mediationDetails->experience) ? $mediationDetails->experience : '' }}</textarea>
                                            </div>
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td>Circumstances disclosing any past or present relationship with, or interest
                                            in, any of the parties or in relation to the subject-matter in dispute,
                                            whether financial, business, professional or other kind, which is likely to
                                            impair your independence or impartiality (list out)</td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" name="particulars2" id="particulars2" rows="3">NA</textarea>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Circumstances which are likely to affect your ability to devote sufficient
                                            time to the mediation and in particular your ability to complete the entire
                                            mediation within the time limits prescribed</td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" name="particulars3" id="particulars3" rows="3">NA</textarea>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr hidden>
                                        {{-- <td>ability to complete the entire mediation within the time limits prescribed</td> --}}
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" name="particulars4" id="particulars4" rows="3">NA</textarea>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required id="confirm" checked>
                                <label class="form-check-label" for="confirm">
                                    I confirm that the details provided above are true, accurate, current and complete
                                    and acknowledge that a copy of the consent and disclosures will be provided to the
                                    parties.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="accept"
                                    checked>
                                <label class="form-check-label" for="accept">
                                    By checking this box, I accept and agree to conduct the mediation in accordance with
                                    the Rules and confirm that I shall abide by the <a
                                        href="https://drive.google.com/file/d/1M6dHbOuIQv4OZlhgyRFiDu75CsSKeZUI/view">Code</a>,
                                    <a href="https://presolv360.com/terms_conditions">Terms & Conditions</a> and <a
                                        href="https://presolv360.com/privacy_policy">Privacy Policy</a>.
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Accept</button>
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

@endsection


@section('footer')
<!-- Datatable plugin js -->
<script src="{{ url('/') }}/assets/libs/datatables/jquery.dataTables.min.js"></script>
<script src="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Datatables init -->
<script src="{{ url('/') }}/assets/js/pages/datatables.init.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>
    function pad(str, max) {
        str = str.toString();
        return str.length < max ? pad("0" + str, max) : str;
    }
    var userTable = $('#request').DataTable({
        "serverMethod": "POST",
        "sAjaxSource": '{{ route('mediator.newjson') }}',
        "processing": true,
        "serverSide": true,
        // "responsive": true,
        "lengthMenu": [
            [10, 25, 50, 100, 250, 500, 1000],
            [10, 25, 50, 100, 250, 500, 1000],
        ],
        "iDisplayLength": 25,
        "columns": [{
                "data": "key",
                render: function(data, type, row, meta) {
                    var button = "";
                    button = button + `<input type="checkbox" class="blkchk" data-caseid="` + row
                        .caseId + `" data-mediatorId="` + row.mediator_id + `">`;
                    return meta.row + meta.settings._iDisplayStart + 1 + button;
                }
            },
            // {
            //     "data": "id",
            //     render: function(data, type, row) {
            //         var button = "";
            //         button = button + `<input type="checkbox" class="blkchk" data-caseid="` + row
            //             .caseId + `" data-mediatorId="` + row.mediator_id + `">`;
            //         return button;
            //     }
            // },
            {
                "data": "caseId",
                render: function(data, type, row) {
                    return "M" + pad(data, 6)
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
                    return row.date
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
                                    d = d +
                                        `<p class="text-success party_name get_party mb-0" data-phone="` +
                                        data[i].userPhone + `" data-email="` + data[i].userEmail +
                                        `" data-address="` + data[i].address1 + " " + data[i].address2 +
                                        `">` + data[i].organization + `</p>`;

                                    
                                    var ip_name = data[i].name;
                                    if (ip_name != "") {
                                        d = d +
                                            `<span class="text-success party_name get_party" data-phone="` +
                                            data[i].userPhone + `" data-email="` + data[i].userEmail +
                                            `" data-address="` + data[i].address1 + " " + data[i]
                                            .address2 +
                                            `">` + ip_name + `</span><br>`;
                                    }
                                    
                                } else {
                                    d = d +
                                        `<p class="text-success party_name get_party" data-phone="` +
                                        data[i].userPhone + `" data-email="` + data[i].userEmail +
                                        `" data-address="` + data[i].address1 + " " + data[i].address2 +
                                        `">` + data[i].name + `</p>`;
                                }

                            }
                        } else {
                            if (data[i].name != null) {
                                // if (data[i].address1 != null) {
                                //     d = d + `<p class="text-danger get_party " data-phone="` + data[i]
                                //         .userPhone + `" data-email="` + data[i].userEmail +
                                //         `" data-address="` + data[i].address1 + " " + data[i].address2 +
                                //         `">` + data[i].name + `</p>`;
                                // } else {
                                //     d = d + `<p class="text-danger get_party " data-phone="` + data[i]
                                //         .userPhone + `" data-email="` + data[i].userEmail +
                                //         `" data-address="` + data[i].fulladdress + `">` + data[i].name +
                                //         `</p>`;
                                // }

                                if (data[i].isClaimant == 0) {
                                    d = d + `<span class="text-success get_party mb-0" data-inid="` + data[
                                            i].id + `" data-id="` + data[i].userId + `">` + data[i]
                                        .name + `</span><br>`;
                                } else {
                                    if (data[i].address1 != null) {
                                        d = d + `<p class="text-danger get_party mb-0" data-phone="` + data[
                                                i]
                                            .userPhone + `" data-email="` + data[i].userEmail +
                                            `" data-address="` + data[i].address1 + " " + data[i]
                                            .address2 +
                                            `">` + data[i].name + `</p>`;
                                    } else {
                                        d = d + `<p class="text-danger get_party mb-0" data-phone="` + data[
                                                i]
                                            .userPhone + `" data-email="` + data[i].userEmail +
                                            `" data-address="` + data[i].fulladdress + `">` + data[i]
                                            .name +
                                            `</p>`;
                                    }
                                }
                            }
                        }
                        */
                    }
                    d = d + d_ip + d_rp;
                    return d;
                }
            },
            // {
            //     "data": "caseId",
            //     render: function(data, type, row) {
            //         var button = "";
            //         button = button +
            //             `<div class="position-relative"> <button type="button"  data-type="1" data-typename="Private" data-id="` +
            //             data +
            //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-purple btn-sm waves-effect ">Private</button>`;
            //         button += ` <span class="badge-success badge private_total">` + row.private_count +
            //             `</span>`;
            //         if (row.private_view_count != 0) {
            //             button += ` <span class="badge badge-danger private_unseen">` + row
            //                 .private_view_count + `</span>`;
            //         }
            //         button = button +
            //             ` <button type="button" data-type="0" data-typename="Share" data-id="` + data +
            //             `"  data-toggle="modal" data-target="#commentModal" class="btn btn-dark btn-sm waves-effect ">Share</button>`;
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
                "data": "caseId",
                render: function(data, type, row) {

                    // if(data==1){
                    //   var button = `<button class="btn-sm btn-danger" value="`+data.id+`" id="statuschang">Reject</button>`;
                    //     return button;  
                    // }else{
                    // }

                    var button = `<button class="btn btn-success btn-sm acceptBtn" data-issue="` + row
                        .case_issue + `" data-toggle="modal" data-target="#acceptModal" data-caseid="` +
                        row.caseId + `" data-mediatorId="` + row.mediator_id + `">Accept</button>
                                    <button class="btn btn-danger btn-sm" id="statuschang" data-caseid="` + row
                        .caseId + `" data-mediatorId="` + row.mediator_id + `">Reject</button>`;
                    return button;



                    //   var button = `<div class="form-group">
                    //   <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    //     <input type="checkbox" name=="user_status" id="customSwitch` + row.id + `" value="` + row.id + `" class="custom-control-input statuschang" ` + ((data == 1) ? "checked" : "") + `>
                    //     <label class="custom-control-label" for="customSwitch` + row.id + `"> </label>
                    //   </div>
                    // </div>`;
                    //   return button;
                }
            }
        ],
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
                mediation_case_id: item.id,
                _token: item.token,
                allcids: item.allcids,
                total_row: item.total_row,
                log_id: logId,
                insertRow: insId,
                faildRow: failId,
                status: item.status,
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
                $("#mess").append(
                    "<center>Case Id A00" + error.caseid + " Failed.</center>"
                );
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

    var ajax_request_Accept = function(item, url) {
        var deferred = $.Deferred();

        $.ajax({
            url: url,
            dataType: "json",
            type: "POST",
            data: {
                mediation_case_id: item.id,
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
                        //console.log('insId '+insId);
                    } else {
                        insId = insId + "," + result.caseid;
                        // console.log('insId d '+insId);
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
                $("#mess").append(
                    "<center>Case Id A00" + error.caseid + " Failed.</center>"
                );
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
    // function end 

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
                        swal("comment save successfully!", {
                            icon: "success",
                        }).then(function() {
                            // location.reload();
                        });
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
                swal("comment not added!");
            }
            userTable.ajax.reload(null, false);

        });
        return false;
    });
    $('#acceptModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var data = button.parent().parent().find(".get_party");
        var caseid = button.data('caseid');
        var issue = button.data('issue');
        var modal = $(this);
        $(this).data("address") + `</td><td></td></tr>`

        var dd = "";
        data.each(function(index) {
            if (index <= 1) {
                if (index == 0) {
                    dd = dd + `<tr>`;
                    dd = dd +
                        `<td>
                        <p>` + $(this).text() + `</p>
                        <p>` + $(this).data("address") + `</p>
                        </p>` + $(this).data("phone") + `</p>
                        </p>` + $(this).data("email") + `</p>
                        </td>`;
                }
                if (index > 0) {
                    dd = dd +
                        `<td>
                            <p>` + $(this).text() + `</p>
                            <p>` + $(this).data("address") + `</p>
                            </p>` + $(this).data("phone") + `</p>
                            </p>` + $(this).data("email") + `</p>
                        </td>`;
                    dd = dd + `</tr>`;
                }

            } else {
                dd = dd + `<tr>
                    <td></td>
                    <td>
                        <p>` + $(this).text() + `</p>
                        <p>` + $(this).data("address") + `</p>
                        </p>` + $(this).data("phone") + `</p>
                        </p>` + $(this).data("email") + `</p>
                    </td>
                    `;
            }
        });
        modal.find("#partyDetails").find("tbody").html(dd);
        modal.find('.modal-title').text('Accept Request To : ' + "M" + pad(caseid, 6));
        modal.find('input[name="mediation_case_id"]').val(caseid);
        modal.find('#issueModal').text(issue);
    })
    $(document).on('submit', "#acceptForm", function() {
        swal({
            title: "Are you sure?",
            text: "to accept this request!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: '{{ route('mediator.activeDeactive') }}',
                    method: "post",
                    data: $('#acceptForm').serialize(),
                    beforeSend: function() {
                        swal({
                            title: 'Loading...',
                            showConfirmButton: false,
                            buttons: false,

                        });
                    },
                }).done(function(data) {
                    userTable.ajax.reload()
                    swal("Request Accepted!", {
                        icon: "success",
                    }).then(function() {
                        location.reload();
                    });

                });
                $("#acceptModal").modal("hide");
            } else {
                $('#acceptForm').find("textarea[name='particulars2']").val("");
                $('#acceptForm').find("textarea[name='particulars2']").text("");
                $('#acceptForm').find("textarea[name='particulars3']").val("");
                $('#acceptForm').find("textarea[name='particulars3']").text("");
                $('#acceptForm').find("textarea[name='particulars4']").val("");
                $('#acceptForm').find("textarea[name='particulars4']").text("");
                swal("Your imaginary file is safe!");
            }
        });
        return false;
    });
    $(document).on('click', "#statuschang", function() {
        var caseid = $(this).data('caseid');
        var mediatorid = $(this).data('mediatorid');
        var do_action = $(this).html();

        var csrf = document.querySelector('meta[name="csrf-token"]').content;



        /*on reject case*/
        if (do_action == 'Reject') {
            var status = 2;

            swal({
                title: "Are you sure?",
                text: "to Reject these request!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '{{ route('mediator.activeDeactive') }}',
                        method: "post",
                        data: {
                            mediation_case_id: caseid,
                            mediator_id: mediatorid,
                            status: status,
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
                        swal("Request Rejected!", {
                            icon: "success",
                        }).then(function() {
                            location.reload();
                        });
                    });

                } else {
                    swal("Your imaginary file is safe!");
                }
            });




        }


        // var csrf = document.querySelector('meta[name="csrf-token"]').content;

    });


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

    $("#bulkAcceptBtn").click(function() {
        var ctcnt = 0;

        var blkclon = false;

        var arbid = $(this).data("arb");


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
                // text: ctcnt + " cases selected to accept",
                title: "Are you sure?",
                text: ctcnt + " Cases selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function(willDelete) {
                if (willDelete) {
                    var cids = null;
                    var idarr = [];
                    var ctcnt = 0;
                    var result = {};

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
                            var csrf = document.querySelector('meta[name="csrf-token"]')
                                .content;

                            $.each($('#acceptForm').serializeArray(), function() {
                                result[this.name] = this.value;
                            });

                            // at this stage the result object will look as expected so you could use it
                            // alert('name1 = ' + result.name1 + ', name2 = ' + result.name2);
                            idarr.push({
                                id: $(this).data("caseid"),
                                allcids: cids,
                                fsData: result,
                                total_row: ctcnt,
                                log_type: "Bulk Accept ",
                            });
                        }
                    });

                    var burl = '{{ route('mediator.activeDeactive') }}';
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

                                    return ajax_request_Accept(item, burl);

                                });
                                return looper;

                            })
                        )
                        .then(function() {
                            swal.close();
                            // $("#myModalcc").hide();
                            $("#messccclose").append(
                                '<br><center><a href="{{ route('mediator.newrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                    // $(".blkchk").each(function() {
                    //     if (this.checked) {
                    //         var id = $(this).data("caseid");

                    //         $('#acceptModal').find('input[name="mediation_case_id"]').val(id);

                    //         $.ajax({
                    //             url: '{{ route('mediator.activeDeactive') }}',
                    //             method: "post",
                    //             data: $('#acceptForm').serialize(),
                    //             beforeSend: function() {
                    //                 swal({
                    //                     title: 'Loading...',
                    //                     showConfirmButton: false,
                    //                     buttons: false,

                    //                 });
                    //             },
                    //         }).done(function(data) {
                    //             userTable.ajax.reload()
                    //             swal("Request Accepted!", {
                    //                 icon: "success",
                    //             }).then(function() {
                    //                 location.reload();
                    //             });
                    //         });
                    //         // console.log(ids);
                    //     }
                    // });

                } else {
                    $('#acceptForm').find("textarea[name='particulars2']").val("");
                    $('#acceptForm').find("textarea[name='particulars2']").text("");
                    $('#acceptForm').find("textarea[name='particulars3']").val("");
                    $('#acceptForm').find("textarea[name='particulars3']").text("");
                    $('#acceptForm').find("textarea[name='particulars4']").val("");
                    $('#acceptForm').find("textarea[name='particulars4']").text("");
                    swal("Your imaginary file is safe!");
                }



            });

            // swal
            //   .queue([
            //     {
            //       title: "Are you sure?",
            //       text: ctcnt + " cases selected to accept",
            //       type: "info",
            //       allowOutsideClick: false,
            //       showCancelButton: true,
            //       confirmButtonColor: "#41B314",
            //       cancelButtonColor: "#F9354C",
            //       confirmButtonText: "Confirm",
            //       showLoaderOnConfirm: true,
            //       preConfirm: function () {
            //         var ids = null;

            //         $(".blkchk").each(function () {
            //           if (this.checked) {
            //             var id = $(this).data("caseid");

            //             if (ids == null) {
            //               ids = id;
            //             } else {
            //               ids = ids + "," + id;
            //             }
            //             console.log(ids);
            //           }

            //         });

            //         // $.ajax({
            //         //   url: DOMAIN + "functions/ajx_requests.php",
            //         //   method: "POST",
            //         //   dataType: "JSON",
            //         //   data: { ids: ids, arbid: arbid, case: "arbacceptarbblk" },
            //         //   success: function (result) {
            //         //     console.log(result);

            //         //     if (JSON.parse(result["result"]).response == "success") {
            //         //       swal({
            //         //         title: "Arbitrations accepted",
            //         //         text: "",
            //         //         type: "success",
            //         //       });

            //         //       setTimeout(function () {
            //         //         window.location.reload();
            //         //       }, 5000);
            //         //     } else {
            //         //       swal({
            //         //         title: "Please try again",
            //         //         text: "",
            //         //         type: "error",
            //         //       });
            //         //     }
            //         //   },
            //         //   error: function (err) {
            //         //     console.log(err);
            //         //   },
            //         // });
            //       },
            //     }
            //   ]);
            //   .catch(swal.noop);
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

            var status = 2;
            swal({
                title: "Are you sure?",
                text: ctcnt + " Cases selected",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var cids = null;
                    var Status = $('#status_id').val();
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
                            var Status = $('#status_id').val();
                            var csrf = document.querySelector('meta[name="csrf-token"]')
                                .content;

                            idarr.push({
                                id: caseid,
                                token: csrf,
                                allcids: cids,
                                total_row: ctcnt,
                                mediator_id: mediatorid,
                                status: status,
                                log_type: "Bulk reject ",
                            });
                        }
                    });
                    var burl = '{{ route('mediator.activeDeactive') }}';
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
                                '<br><center><a href="{{ route('mediator.newrequest') }}" class="btn btn-danger btn-lg">Close</a></center>'
                            );
                            var objDiv = document.getElementById("messcc");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        });
                    // $(".blkchk").each(function() {
                    //     if (this.checked) {
                    //         var csrf = document.querySelector('meta[name="csrf-token"]')
                    //             .content;
                    //         var caseid = $(this).data("caseid");

                    //         $.ajax({
                    //             url: '{{ route('mediator.activeDeactive') }}',
                    //             method: "post",
                    //             data: {
                    //                 caseid: caseid,
                    //                 mediator_id: mediatorid,
                    //                 status: status,
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
                    //             userTable.ajax.reload()
                    //             swal("Request Rejected!", {
                    //                 icon: "success",
                    //             }).then(function() {
                    //                 location.reload();
                    //             });
                    //         });
                    //     }
                    // });

                } else {
                    swal("Your imaginary file is safe!");
                }
            });

        }
    });
</script>

@endsection
