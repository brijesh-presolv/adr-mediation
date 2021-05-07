@extends('mediator.layouts.app')
@section('title', 'Users')

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">New request </a></li>
<!-- end page title -->
@endsection

@section('content')


@section('pageTitleOnDashboard')
<h4 class="page-title">New Request</h4>
@endsection


<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <table  id="request" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>Sr. No</th>
                        <th>Case Id</th>
                        <th>Date</th>
                        <th>Party Details</th>
                        <th>Comments</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
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
                <input type="hidden" name="mediation_case_id">
                <input type="hidden" name="status" value="1">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h5>Mediator’s Consent and Disclosures</h5>
                            <p>See Rule 6 of Section 3 of Presolv360’s Dispute Resolution Rules (“Rules”) read with the Arbitrators’ and Mediators’ Code of Conduct and Disclosure Rules (“Code”)</p>
                        </div>
                        <div class="col-lg-12">
                            <p><u>Details of the Dispute</u></p>

                            <p>Initiating Party Details:  details to appear here</p>

                            <p> Responding Party Details: details to appear here</p>

                            <p>Details of Dispute as per Initiating Party: details to appear here</p>

                        </div>
                        <div class="col-lg-12">
                            <table class="table table-bordered">
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
                                                    <input class="form-check-input" type="radio" name="consent1" id="consent1yes" value="1">
                                                    <label class="form-check-label" for="consent1yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent1" id="consent1no" value="0">
                                                    <label class="form-check-label" for="consent1no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I am qualified, possess the required competence, knowledge and expertise, and have sufficient time to be able to conduct the mediation proceedings within the time limits prescribed in the Rules</td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent2" id="consent2yes" value="1">
                                                    <label class="form-check-label" for="consent2yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent2" id="consent2no" value="0">
                                                    <label class="form-check-label" for="consent2no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I shall be, and remain, independent and neutral throughout the proceedings i.e. from beginning to end and ensure that my words, manner, attitude, body language and process management reflects an impartial and even-handed approach</td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent3" id="consent3yes" value="1">
                                                    <label class="form-check-label" for="consent3yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent3" id="consent3no" value="0">
                                                    <label class="form-check-label" for="consent3no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I shall conduct the mediation proceedings in a fair and impartial manner, and endeavour to provide a procedurally fair process in which each party is given an adequate opportunity to participate </td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent4" id="consent4yes" value="1">
                                                    <label class="form-check-label" for="consent4yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent4" id="consent4no" value="0">
                                                    <label class="form-check-label" for="consent4no">
                                                        No
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>I shall maintain utmost confidentiality of all matters relating to mediation proceedings, including all documents, records, and communications, during as well as after its completion</td>
                                        <td>
                                            <div class="form-inline">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent5" id="consent5yes" value="1">
                                                    <label class="form-check-label" for="consent5yes">
                                                        Yes
                                                    </label>
                                                </div>
                                                &nbsp;&nbsp;
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="consent5" id="consent5no" value="0">
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
                                    <tr>
                                        <td>Experience</td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" cols="150" name="particulars1" id="particulars1" rows="3">{{isset($mediationDetails->experience)?$mediationDetails->experience:""}}</textarea>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Circumstances disclosing any past or present relationship with, or interest in, any of the parties or in relation to the subject-matter in dispute, whether financial, business, professional or other kind, which is likely to impair your independence or impartiality (list out)</td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" name="particulars2" id="particulars2" rows="3"></textarea>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Circumstances which are likely to affect your ability to devote sufficient time to the mediation and in particular your </td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" name="particulars3" id="particulars3" rows="3"></textarea>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ability to complete the entire mediation within the time limits prescribed under the Rules</td>
                                        <td>
                                            <div class="form-group">
                                                <textarea class="form-control" name="particulars4" id="particulars4" rows="3"></textarea>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" required id="confirm">
                                <label class="form-check-label" for="confirm">
                                    I confirm that the details provided above are true, accurate, current and complete and acknowledge that a copy of the consent and disclosures will be provided to the parties.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="accept">
                                <label class="form-check-label" for="accept">
                                    By checking this box, I accept and agree to conduct the mediation in accordance with the Rules and confirm that I shall abide by the Code, Terms & Conditions and Privacy Policy.
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
@endsection

<!-- Table datatable css -->
@section('head')

<link href="{{ url('/') }}/assets/libs/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="{{ url('/') }}/assets/libs/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
    "ajax": '{{ route('mediator.newjson') }}',
    "responsive": true,
    "order": [[ 1, "desc" ]],
    "columns": [
        {"data": "id"},
        {"data": "caseId",
            render: function (data, type, row) {
                return "M" + pad(data, 6)
            }
        },
        {"data": "date",
            render: function (data, type, row) {
                return row.date
            }
        },
        {"data": "party",
            render: function (data, type, row) {
                var d = "";
                for (i in data) {
                    if (data[i].isOnboarded == 1) {
                        d = d + `<p class="text-success">` + data[i].name + `</p>`;
                    } else {
                        d = d + `<p class="text-danger">` + data[i].name + `</p>`;
                    }
                }
                return d;
            }
        },
        {"data": "comments",
            render: function (data, type, row) {

                var button = `<button class="btn   btn-sm btn-primary label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',1,'425')">Private</button>
                            <button class="btn  btn-sm  btn-success label label-success " data-toggle="modal" data-target="#myModalcomment" onclick="arbcommentmodal('1243',2,'a')">Shared</button>`;
                return button;
            }
        },
        {"data": "caseId",
            render: function (data, type, row) {

// if(data==1){
//   var button = `<button class="btn-sm btn-danger" value="`+data.id+`" id="statuschang">Reject</button>`;
//     return button;  
// }else{
// }

                var button = `<button class="btn-sm btn-success acceptBtn" data-toggle="modal" data-target="#acceptModal" data-caseid="` + row.caseId + `" data-mediatorId="` + row.mediator_id + `">Accept</button>
                                    <button class="btn-sm btn-danger" id="statuschang" data-caseid="` + row.caseId + `" data-mediatorId="` + row.mediator_id + `">Reject</button>`;
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
$('#acceptModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var caseid = button.data('caseid');
    var modal = $(this);
    modal.find('.modal-title').text('Accept Request To : ' + "M" + pad(caseid, 6));
    modal.find('input[name="mediation_case_id"]').val(caseid);
})
$(document).on('submit', "#acceptForm", function () {
    swal({
        title: "Are you sure?",
        text: "to accept this request!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: '{{ route("mediator.activeDeactive") }}',
                method: "post",
                data: $('#acceptForm').serialize(),
            }).done(function (data) {
                userTable.ajax.reload()
                swal("Request Accepted!", {
                    icon: "success",
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
$(document).on('click', "#statuschang", function () {
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
                    url: '{{ route("mediator.activeDeactive") }}',
                    method: "post",
                    data: {caseid: caseid, mediator_id: mediatorid, status: status, '_token': csrf},
                }).done(function (data) {
                    userTable.ajax.reload()
                    swal("Request Rejected!", {
                        icon: "success",
                    });
                });

            } else {
                swal("Your imaginary file is safe!");
            }
        });




    }


// var csrf = document.querySelector('meta[name="csrf-token"]').content;

});






</script>

@endsection





















