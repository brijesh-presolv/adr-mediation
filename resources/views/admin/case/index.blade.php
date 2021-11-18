@extends('admin.layouts.app')
@section('title', "New Request")

@section('breadcrumb')
<!-- start page title -->
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.home')</a></li>
<li class="breadcrumb-item"><a href="javascript: void(0);">@lang('case.new_request')</a></li>
<!-- end page title -->
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box table-responsive">
            <div class="row">
                <div class="col-md-12">
                        <button class="btn btn-primary btn-sm" data-target="#myModalbupldAdmin" data-toggle="modal"> Bulk Upload</button>
                  <br>
                  <br>
                </div>
           </div>
           <div id="myModalbupldAdmin" class="mdladcm modal fade " role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        {{-- {{dd($allUsers)}} --}}
                        <div class="blkfrmdiv">
                            <h3>Upload .csv file</h3>
                           <form enctype="multipart/form-data" method="post" action="{{route('admin.bulkUpload')}}">
                            {{ csrf_field() }}
                                    <input type="hidden" name="token" id="token_input">
                                    <div class="form-group">
                                    <select class="form-control" name="claimant"  required>
                                        <option value="">@lang('Select Claimant')</option>
                                        @foreach($allUsers as $value)
                                        @if ($value->isActive)
                                            <option value="{{$value->id}}">{{$value->first_name}} {{$value->last_name}} - {{$value->organization}}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    {{-- <input type="hidden" name="claimant" value="{{auth()->user()->id}}"> --}}
                                    </div>
                                     {{-- <input type="hidden" name="uploaded_by" value="{{auth()->user()->id}}" /> --}}

                                <div class="form-group">
                                    <input type="file" name="csv" id="fileInput" onchange="" class="col-md-12 dropify" data-allowed-file-extensions="csv" required="" data-max-file-size="20M" />
                                </div>

    <input type="Submit"  value="Submit" class="btn btn-primary blkupdbtnsb">
    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
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
            <table  id="users" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('case.serial_number')</th>
                        <th>@lang('case.case_id')</th>
                        <th>@lang('case.date') <a href="#" data-toggle="tooltip" title="" data-original-title="Date and time of raising the 'Request for Mediation'."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.case_details') <a href="#" data-toggle="tooltip" title="" data-original-title="Click here to view the 'Request for Mediation'."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                        <th>@lang('case.party_details')</th>
                        <th>@lang('Supporting Document')</th>
                        <th>@lang('case.action') <a href="#" data-toggle="tooltip" title="" data-original-title="Click 'Confirm' to register the Mediation (after assigning an mediator). Click 'Reject' to decline the Mediation."><i class="fa fa-info-circle" aria-hidden="true"></i></a></th>
                    </tr>
                </thead>
            </table>
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
                        <select class="form-control" name="midater"  required>
                            <option value="">@lang('case.form_select_mediator')</option>
                            @foreach($users as $user)
                            @if ($user->isActive)
                                <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} - {{$user->organization}}</option>
                            @endif
                            @endforeach
                        </select>
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
                var button = "M" + pad(data, 6);
                    button = button+`<br><a href="{{ url('admin/track/') }}/` + data + `" class="btn btn-secondary waves-effect  waves-light btn-sm" title="Track">Track</a> `
                    return button;
            }
        },
        {"data": "date"},
        {"data": "case.id",
            render: function (data,type,row) {
                var d='';

                if(row.party.length==0){
                     d="disabled";
                }
                var button = ` <a href="{{ url('admin/casedetails/') }}/` + data + `" class="btn btn-primary waves-effect  waves-light btn-sm `+d+`" title="@lang('case.btn_case_details_view')"><i class="mdi mdi-file-eye-outline"></i></a> `;
                button = button+ ` <a href="{{ url('admin/updatecase/') }}/` + data + `" class="btn btn-info waves-effect waves-light btn-sm `+d+`" title="@lang('case.btn_case_details_edit')"><i class="mdi mdi-content-save-edit-outline"></i></a> `;
                return button;
            }
        },
        {"data": "party",
            render: function (data, type, row) {
                var d = "";
                for (i in data) {
                    // console.log(data[i].documentPath);

                    if (data[i].isOnboarded == 1) {
                        d = d + `<span class="text-success">` + data[i].name + `</span><br>`;
                    } else {
                        d = d + `<span class="text-danger">` + data[i].name + `</span><br>`;
                    }
                }

                if(d==''){

                    return `<span class="text-danger">@lang('case.status_pending')</span><br>`;
                }
                return d;
            }
        },
        {"data": "case",
            render: function (data, type, row) {
                var d = "";
                // for (i in data) {

                    if (data.documentPath != 'NULL' && data.documentPath != '') {
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
        
        {"data": "case.id",
            render: function (data, type, row) {

                 var d='';

                if(row.party.length==0){
                     return button="@lang('case.na')";
                }

                var button = "";
                button = button + `<button value="` + data + `"  data-id="` + data + `" data-toggle="modal" data-target="#midaterAdd"  class="btn btn-info `+d+`">@lang('case.btn_confirm')</button>`;
                button = button + ` <button value="` + data + `" class="btn btn-danger reject `+d+`">@lang('case.btn_reject')</button>`;
                return button;
            }
        },
    ],
});
$(document).on('click', ".reject", function () {
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
                url: '{{ route("admin.case.reject_status") }}',
                method: "post",
                data: {id: id, '_token': csrf},
            }).done(function (data) {
                userTable.ajax.reload();
                swal("@lang('case.reject_successfully')", {
                    icon: "success",
                });
            });
        } else {
            swal("@lang('case.cansel_reject_request')");
        }
    });
});
$(document).on('submit', "#MidaterForm", function () {
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
                url: '{{ route("admin.case.midater_add") }}',
                method: "post",
                data: {id: id, midater: midater, '_token': csrf},
            }).done(function (data) {
                swal("@lang('case.mediator_assigned_successfully')", {
                    icon: "success",
                });
                $.ajax({
                    url: '{{ route("admin.case.confirm_status") }}',
                    method: "post",
                    data: {id: id, '_token': csrf},
                }).done(function (data) {
                    userTable.ajax.reload()
                    swal("@lang('case.confirm_successfully')", {
                        icon: "success",
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
$('#midaterAdd').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var recipient = button.data('id') // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this)
    modal.find('.modal-body input[name="id"]').val(recipient)
})

<?php if(session()->has('success')) {?>
        swal({
            title: '{{session()->get("success")}}',
            // text: "Withdraw case!",
            icon: "success",
            buttons: true,
        }).then(function() {
    window.location ="{{route('admin.case.newrequest')}}"});

    <?php } if(session()->has('error')) {?>
        swal({
            title: "Error",
            text: '{{session()->get("error")}}',
            icon: "error",
            buttons: true,
            dangerMode: true,
        }).then(function() {
    window.location ="{{route('admin.case.newrequest')}}"});
    <?php } ?>
</script>
@endsection
